<?php
namespace Db;

use Base\Config;
use Base\Logger;

class MySQL {
    private $pdo = null;
    private $stmt = null;

    public function __construct() {
        if (!isset($this->pdo)) {
            try {
                if (!$this->pdo) {
                    $config = Config::get('service.database.master');
                    $option = array(
                        \PDO::ATTR_CASE => \PDO::CASE_LOWER,
                    );
                    if (php_sapi_name() == 'cli') {
                        $option = array(\PDO::ATTR_PERSISTENT => true);
                    }
                    $this->pdo = new \PDO($config['dsn'], $config['username'], $config['password'], array_filter($option));
                }
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }
        return $this->pdo;
    }

    /**
     * SQL查询
     *
     * @param       $sql
     * @param array $params
     * @return bool|int|null
     */
    public function query($sql, $params = array()) {
        $this->exec($sql, $params);
        $tags = explode(' ', trim($sql), 2);
        switch (strtoupper($tags[0])) {
            case 'SELECT':
                $result = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
            case 'INSERT':
                $result = $this->lastInsertId();
                break;
            case 'UPDATE':
            case 'DELETE':
                $result = $this->stmt->rowCount();
                break;
            default:
                $result = $this->stmt;
        }
        return $result;
    }

    /**
     * 插入数据
     *
     * @param $table_name
     * @param $data
     * @return bool|int|null
     */
    public function insert($table_name, $data) {
        $cols = implode(',', array_map(function ($v) {
            return "`{$v}`";
        }, array_keys($data)));
        $bind = implode(',', array_fill(0, count($data), '?'));
        $params = array_values($data);
        $sql = "insert into `{$table_name}`({$cols}) values({$bind})";
        return $this->query($sql, $params);
    }

    /**
     * 更新数据
     *
     * @param       $table_name
     * @param array $data
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     */
    public function update($table_name, array $data = array(), array $where = array(), array $order = array(), $limit = 0) {
        $params = array();
        foreach ($data as $key => $value) {
            $set_sql[] = "`{$key}` = ?";
            $params[] = $value;
        }
        $where_sql = $this->toSql($where, $order, $limit);
        $sql = "update `{$table_name}` set " . implode(', ', $set_sql) . " where {$where_sql['sql']}";
        return $this->query($sql, array_merge($params, $where_sql['params']));
    }

    /**
     * 删除数据
     *
     * @param       $table_name
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     */
    public function delete($table_name, array $where = array(), array $order = array(), $limit = 0) {
        $where_sql = $this->toSql($where, $order, $limit);
        $sql = "delete from {$table_name} where {$where_sql['sql']}";
        return $this->query($sql, $where_sql['params']);
    }

    /**
     * 查询多条数据
     *
     * @param        $table_name
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @param int    $limit
     * @return bool|int|null
     */
    public function select($table_name, array $where = array(), $cols = '*', array $order = array(), $limit = 0) {
        if (is_array($cols)) {
            $cols = implode(',', array_map(function ($v) {
                return "`{$v}`";
            }, array_values($cols)));
        } elseif (!$cols) {
            $cols = '*';
        }
        $where_sql = $this->toSql($where, $order, $limit);
        $sql = "select {$cols} from {$table_name} where {$where_sql['sql']}";
        return $this->query($sql, $where_sql['params']);
    }

    /**
     * 查打单条数据
     *
     * @param        $table_name
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @return array
     */
    public function find($table_name, array $where = array(), $cols = '*', array $order = array()) {
        $result = $this->select($table_name, $where, $cols, $order, 1);
        return $result ? $result[0] : [];
    }

    /**
     * 开启
     */
    public function begin() {
        $this->pdo->beginTransaction();
    }

    /**
     * 提交
     */
    public function commit() {
        $this->pdo->commit();
    }

    /**
     * 回滚
     */
    public function rollback() {
        $this->pdo->rollBack();
    }

    /**
     * 生成sql条件
     *
     * @param array $param
     * @param array $order
     * @param int   $limit
     * @return array
     */
    public function toSql(array $param = array(), array $order = array(), $limit = 0) {
        $sql = '';
        $where = array();
        $params = array();
        if (!empty($param)) {
            foreach ($param as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['start']) || isset($value['end'])) {
                        if ($value['start']) {
                            $where[] = "`{$key}` >= ?";
                            $params[] = $value['start'];
                        }
                        if ($value['end']) {
                            $where[] = "`{$key}` <= ?";
                            $params[] = $value['end'];
                        }
                    } elseif (isset($value['like'])) {
                        $where[] = "`{$key}` like ?";
                        $params[] = $value['like'];
                    } elseif (isset($value['neq'])) {
                        $where[] = "`{$key}` != ?";
                        $params[] = $value['neq'];
                    } else {
                        $in_sql = implode(array_fill(0, count($value), '?'), ',');
                        $where[] = "`{$key}` in (" . $in_sql . ")";
                        $params = array_merge($params, array_values($value));
                    }
                } else {
                    $where[] = "`{$key}` = ?";
                    $params[] = $value;
                }
            }
            $sql .= implode(' and ', $where);
        } else {
            $sql = ' 1 ';
        }

        if (!empty($order)) {
            $order_arr = array();
            foreach ($order as $key => $value) {
                $order_arr[] = "`{$key}` {$value}";
            }
            $sql .= ' order by ' . implode(' and ', $order_arr);
        }

        if ($limit) {
            if (is_array($limit)) {
                $sql .= ' limit ' . intval(array_shift($limit)) . ", " . intval(array_shift($limit));
            } elseif (intval($limit)) {
                $sql .= " limit {$limit}";
            }
        }

        return array('sql' => $sql, 'params' => $params);
    }

    /**
     * 执行查询
     *
     * @param $sql
     * @param $params
     * @return null|\PDOStatement
     * @throws \Exception
     */
    private function exec($sql, $params) {
        //执行
        $_start = microtime(true);
        $this->stmt = $this->pdo->prepare($sql);
        if ($params) {
            $ret = $this->stmt->execute($params);
        } else {
            $ret = $this->stmt->execute();
        }
        if (false === $ret) {
            $error_info = $this->stmt->errorInfo();
            throw new \Exception($error_info[2], $error_info[0]);
        }
        $_end = microtime(true);

        //执行的sql todo debug环境下执行
        $debug_sql = $sql;
        foreach ($params as $param) {
            $debug_sql = substr_replace($debug_sql, $param, strpos($debug_sql, "?"), 1);
        }
        Logger::getInstance()->debug([$_start, $_end, $_end - $_start, $debug_sql], 'sql');

        return $this->stmt;
    }

    /**
     * 得到插入数据的id
     * @param null $name
     * @return bool|int
     */
    public function lastInsertId($name = null) {
        $last = $this->pdo->lastInsertId($name);
        if (false === $last) {
            return false;
        } else if ('0' === $last) {
            return true;
        } else {
            return intval($last);
        }
    }

    /**
     * 关闭数据库连接
     */
    public function __destruct() {
        $this->pdo = null;
    }
}