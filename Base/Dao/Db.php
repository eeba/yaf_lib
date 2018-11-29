<?php
namespace Base\Dao;

use Http\Request;

abstract class Db {

    protected static $db = array();
    protected $table = '';
    protected $field = [];
    protected static $database_name = '';

    abstract static function databaseName();

    public static function getInstance() {
        $database_name = databaseName();
        if (!isset(self::$db[$database_name])) {
            self::$db[$database_name] = new \Db\Mysql($database_name);
            //读写分离
            self::$db[$database_name]->setModeFlag(true);
        }
        return self::$db[$database_name];
    }

    /**
     * 特殊字符转义
     *
     * @param $data
     * @return mixed
     */
    public function security($data) {
        foreach ($data as &$value) {
            $value = htmlspecialchars($value);
        }
        return $data;
    }

    /**
     * 特殊字符反转义
     *
     * @param $data
     * @return mixed
     */
    public function deSecurity($data) {
        foreach ($data as &$value) {
            $value = htmlspecialchars_decode($value);
        }
        return $data;
    }

    /**
     * 事务开始
     */
    public function begin() {
        self::getInstance()->begin();
    }

    /**
     * 提交事务
     */
    public function commit() {
        self::getInstance()->commit();
    }

    /**
     * 回滚
     */
    public function rollback() {
        self::getInstance()->rollback();
    }

    /**
     * 添加
     *
     * @param $data
     * @return bool|int|null
     * @throws \Exception
     */
    public function add($data) {
        return self::getInstance()->insert($this->table, $data);
    }

    /**
     * 更新数据
     *
     * @param array $data
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function update(array $data = array(), array $where = array(), array $order = array(), $limit = 0) {
        return self::getInstance()->update($this->table, $data, $where, $order, $limit);
    }

    /**
     * 根据主键id，查一条数据
     *
     * @param        $id
     * @param string $cols
     * @param array  $order
     * @return array
     * @throws \Exception
     */
    public function findById($id, $cols = '*', array $order = array()) {
        return self::getInstance()->find($this->table, ['id' => $id], $cols, $order);
    }

    /**
     * 查一条数据
     *
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @return array
     * @throws \Exception
     */
    public function find($where = [], $cols = '*', array $order = array()) {
        return self::getInstance()->find($this->table, $where, $cols, $order);
    }

    /**
     * 查多条数据
     *
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @param int    $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function getList(array $where = array(), $cols = '*', array $order = array(), $limit = 0) {
        return self::getInstance()->select($this->table, array_filter($where), $cols, $order, $limit);
    }

    /**
     * 删除
     *
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     * @throws \Exception
     */
    public function delete(array $where = array(), array $order = array(), $limit = 1){
        return self::getInstance()->delete($this->table, $where, $order, $limit);
    }

    /**
     * 直接执行sql
     *
     * @param       $sql
     * @param array $params
     * @return bool|int|null
     * @throws \Exception
     */
    public function query($sql, $params = []) {
        return self::getInstance()->query($sql, $params);
    }

    /**
     * 统计数量
     *
     * @param array $where
     * @return mixed
     * @throws \Exception
     */
    public function count($where = []) {
        $ret = $this->find($where, 'count(1) num');
        return $ret['num'];
    }

    /**
     * 和JQuery DataTables插件配合使用
     *
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @return mixed
     * @throws \Exception
     */
    public function dataTable(array $where = [], $cols = '*', array $order = []) {
        $sql = "select {$cols} from {$this->table} where ";
        $count_sql = "select count(1) num from {$this->table} where ";

        $start = Request::request('start', 0);
        $length = Request::request('length', 10);
        $sql_arr = self::getInstance()->toSql($where, $order, [$start, $length]);
        $content_sql_arr = self::getInstance()->toSql($where, $order);

        $sql .= $sql_arr['sql'];
        $count_sql .= $content_sql_arr['sql'];

        $count = self::getInstance()->query($count_sql, $content_sql_arr['params']);
        $count = $count[0]['num'];

        $data['data'] = self::getInstance()->query($sql, $sql_arr['params']);
        $data['draw'] = Request::request('draw');
        $data['iTotalRecords'] = $count;
        $data['iTotalDisplayRecords'] = $count;

        return $data;
    }

}