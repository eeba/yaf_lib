<?php
namespace Base\Db;

use Http\Request;

class Db {
    private static $db = '';
    protected $table = '';
    protected $field = [];

    public static function db() {
        if (!self::$db) {
            self::$db = new \Db\MySQL();
        }
        return self::$db;
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

    public function begin() {
        self::db()->begin();
    }

    public function commit() {
        self::db()->commit();
    }

    public function rollback() {
        self::db()->rollback();
    }

    /**
     * 添加
     *
     * @param $data
     * @return bool|int|null
     */
    public function add($data) {
        return self::db()->insert($this->table, $data);
    }

    public function update(array $data = array(), array $where = array(), array $order = array(), $limit = 0) {
        return self::db()->update($this->table, $data, $where, $order, $limit);
    }

    public function findById($id, $cols = '*', array $order = array()) {
        return self::db()->find($this->table, ['id' => $id], $cols, $order);
    }

    public function find($where = [], $cols = '*', array $order = array()) {
        return self::db()->find($this->table, $where, $cols, $order);
    }

    public function getList(array $where = array(), $cols = '*', array $order = array(), $limit = 0) {
        return self::db()->select($this->table, array_filter($where), $cols, $order, $limit);
    }

    public function dataTable(array $where = [], $cols = '*', array $order = []) {
        $sql = "select {$cols} from {$this->table} where ";
        $count_sql = "select count(1) num from {$this->table} where ";

        $start = Request::request('start', 0);
        $length = Request::request('length', 10);
        $sql_arr = self::db()->toSql($where, $order, [$start, $length]);
        $content_sql_arr = self::db()->toSql($where, $order);

        $sql .= $sql_arr['sql'];
        $count_sql .= $content_sql_arr['sql'];

        $count = self::db()->query($count_sql, $content_sql_arr['params']);
        $count = $count[0]['num'];

        $data['data'] = self::db()->query($sql, $sql_arr['params']);
        $data['draw'] = Request::request('draw');
        $data['iTotalRecords'] = $count;
        $data['iTotalDisplayRecords'] = $count;

        return $data;
    }

}