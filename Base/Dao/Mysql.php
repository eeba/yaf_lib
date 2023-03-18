<?php

namespace Base\Dao;

use Base\Config;
use Base\Exception;
use Log\Logger;
use PDO;
use PDOStatement;

class Mysql
{
    private string $db_name = '';
    private array $db = [];
    private ?PDO $stmt = null;
    private bool $in_transaction = false; //是否启用了事务
    private bool $mode_flag = false; //是否启用读写分离
    private mixed $config = []; //配置

    const TIMEOUT = 3;
    const WRITE = 'write';
    const READ = 'read';

    /**
     * Mysql constructor.
     * @param string $name 数据库名称
     */
    public function __construct(string $name)
    {
        $this->db_name = $name;
        if (!$this->config) {
            $this->config = Config::get('server.mysql.' . $name);
        }
    }

    protected function getId($mode): string
    {
        return $mode . $this->db_name;
    }

    protected function getDbMode($sql): string
    {
        //不区分读写模式时，直接用写模式，连接master
        if (!$this->mode_flag) {
            return self::WRITE;
        }
        //如果已有开启的写会话,那复用写会话,连接master
        if (isset($this->db[$this->getId(self::WRITE)])) {
            return self::WRITE;
        }
        //事务直接连接master
        if ($this->in_transaction) {
            return self::WRITE;
        }

        return stripos(trim($sql), 'select') !== false ? self::READ : self::WRITE;
    }

    private function connect($config): PDO
    {
        $option = array();
        if (isset($config['pconnect']) && $config['pconnect'] === true) {
            $option[PDO::ATTR_PERSISTENT] = true;
        }
        // MYSQL查询缓存
        $option[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;

        // 错误处理方式，使用异常
        $option[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        // 默认连接后执行的sql
        if (!empty($config['encoding'])) {
            $option[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES '{$config['encoding']}'";
        }

        $option[PDO::ATTR_TIMEOUT] = self::TIMEOUT;
        if (isset($config['timeout'])) {
            $option[PDO::ATTR_TIMEOUT] = $config['timeout'];
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $config['host'], $config['port'], $config['dbname']);
        if (isset($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }
        return new PDO($dsn, $config['username'], $config['password'], $option);
    }

    protected function getPdo($mode = self::READ)
    {
        $key = $this->getId($mode);
        if (!isset($this->db[$key])) {
            $this->db[$key] = $this->connect($this->config[$mode]);
        }
        return $this->db[$key];
    }

    public function setModeFlag($flag): void
    {
        $this->mode_flag = $flag;
    }

    /**
     * SQL查询
     *
     * @param       $sql
     * @param array $params
     * @return PDO|bool|int|null
     * @throws \Exception
     */
    public function query($sql, array $params = array()): PDO|bool|int|null
    {
        $this->exec($sql, $params);
        $tags = explode(' ', trim($sql), 2);
        return match (strtoupper($tags[0])) {
            'SELECT' => $this->stmt->fetchAll(PDO::FETCH_ASSOC),
            'INSERT' => $this->lastInsertId(),
            'UPDATE', 'DELETE' => $this->stmt->rowCount(),
            default => $this->stmt,
        };
    }

    /**
     * 插入数据
     *
     * @param $table_name
     * @param $data
     * @return PDO|bool|int|null
     * @throws \Exception
     */
    public function insert($table_name, $data): PDO|bool|int|null
    {
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
     * @param int $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function update($table_name, array $data = array(), array $where = array(), array $order = array(), int $limit = 0): PDO|bool|int|null
    {
        $params = $set_sql = array();
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
     * @param int $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function delete($table_name, array $where = array(), array $order = array(), int $limit = 1): PDO|bool|int|null
    {
        $where_sql = $this->toSql($where, $order, $limit);
        $sql = "delete from {$table_name} where {$where_sql['sql']}";
        return $this->query($sql, $where_sql['params']);
    }

    /**
     * 查询多条数据
     *
     * @param        $table_name
     * @param array $where
     * @param string $cols
     * @param array $order
     * @param int $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function select($table_name, array $where = array(), string $cols = '*', array $order = array(), int $limit = 0): PDO|bool|int|null
    {
        if (is_array($cols) && !empty($cols)) {
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
     * @param array $where
     * @param string $cols
     * @param array $order
     * @return array
     * @throws \Exception
     */
    public function find($table_name, array $where = array(), string $cols = '*', array $order = array()): array
    {
        $result = $this->select($table_name, $where, $cols, $order, 1);
        return $result ? $result[0] : [];
    }

    /**
     * 开启
     */
    public function begin(): void
    {
        $this->in_transaction = true;
        $this->getPdo(self::WRITE)->beginTransaction();
    }

    /**
     * 提交
     */
    public function commit(): void
    {
        $this->in_transaction = false;
        $this->getPdo(self::WRITE)->commit();
    }

    /**
     * 回滚
     */
    public function rollback(): void
    {
        $this->in_transaction = false;
        $this->getPdo(self::WRITE)->rollBack();
    }

    /**
     * 生成sql条件
     *
     * @param array $param
     * @param array $order
     * @param int $limit
     * @return array
     */
    public function toSql(array $param = array(), array $order = array(), int $limit = 0): array
    {
        $sql = '';
        $where = array();
        $params = array();
        if (!empty($param)) {
            foreach ($param as $key => $value) {
                if (is_array($value)) {
                    if (
                        isset($value['gt']) ||
                        isset($value['gte']) ||
                        isset($value['lt']) ||
                        isset($value['lte']) ||
                        isset($value['neq']) ||
                        isset($value['like'])
                    ) {
                        if (isset($value['gt'])) {//大于
                            $where[] = "`{$key}` > ?";
                            $params[] = $value['gt'];
                        }
                        if (isset($value['gte'])) {//大于等于
                            $where[] = "`{$key}` >= ?";
                            $params[] = $value['gte'];
                        }
                        if (isset($value['lt'])) {//小于
                            $where[] = "`{$key}` < ?";
                            $params[] = $value['lt'];
                        }
                        if (isset($value['lte'])) {//小于等于
                            $where[] = "`{$key}` <= ?";
                            $params[] = $value['lte'];
                        }
                        if (isset($value['like'])) {
                            $where[] = "`{$key}` like ?";
                            $params[] = $value['like'];
                        }
                        if (isset($value['neq'])) {
                            $where[] = "`{$key}` != ?";
                            $params[] = $value['neq'];
                        }
                    } else {
                        $in_sql = implode(',', array_fill(0, count($value), '?'));
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
            $sql .= ' order by ' . implode(' , ', $order_arr);
        }

        if ($limit) {
            if (is_array($limit)) {
                $sql .= ' limit ' . intval(array_shift($limit)) . ", " . intval(array_shift($limit));
            } else {
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
     * @return void
     * @throws Exception
     */
    private function exec($sql, $params): void
    {
        //执行
        $_start = microtime(true);
        $mode = $this->getDbMode($sql);
        $this->stmt = $this->getPdo($mode)->prepare($sql);
        if ($params) {
            $ret = $this->stmt->execute($params);
        } else {
            $ret = $this->stmt->execute();
        }
        if (false === $ret) {
            $error_info = $this->stmt->errorInfo();
            throw new Exception($error_info[2], $error_info[0]);
        }
        $_end = microtime(true);

        //记录执行的sql
        if (defined('DEBUG') && DEBUG) {
            $debug_sql = $sql;
            foreach ($params as $param) {
                $debug_sql = substr_replace($debug_sql, $param, strpos($debug_sql, "?"), 1);
            }
            Logger::debug('sql', [$_start, $_end, $_end - $_start, $debug_sql]);
        }

    }

    /**
     * 得到插入数据的id
     * @param null $name
     * @return bool|int
     */
    public function lastInsertId($name = null): bool|int
    {
        $last = $this->getPdo(self::WRITE)->lastInsertId($name);
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
    public function __destruct()
    {
        $read_id = $this->getId(self::READ);
        if (isset($this->db[$read_id])) {
            $this->db[$read_id] = null;
        }
        $write_id = $this->getId(self::WRITE);
        if (isset($this->db[$write_id])) {
            $this->db[$write_id] = null;
        }

        return true;
    }
}