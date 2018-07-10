<?php
namespace Base\Dao;

use Http\Request;

class Db {
    private $db = '';
    protected $table = '';
    protected $field = [];

    public function db() {
        if (!$this->db) {
            $this->db = new \Db\MySQL();
        }
        return $this->db;
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
        $this->db()->begin();
    }

    public function commit() {
        $this->db()->commit();
    }

    public function rollback() {
        $this->db()->rollback();
    }

    /**
     * 添加
     *
     * @param $data
     * @return bool|int|null
     */
    public function add($data) {
        return $this->db()->insert($this->table, $data);
    }

    public function update(array $data = array(), array $where = array(), array $order = array(), $limit = 0) {
        return $this->db()->update($this->table, $data, $where, $order, $limit);
    }

    public function findById($id, $cols = '*', array $order = array()) {
        return $this->db()->find($this->table, ['id' => $id], $cols, $order);
    }

    public function find($where = [], $cols = '*', array $order = array()) {
        return $this->db()->find($this->table, $where, $cols, $order);
    }

    public function getList(array $where = array(), $cols = '*', array $order = array(), $limit = 0) {
        return $this->db()->select($this->table, array_filter($where), $cols, $order, $limit);
    }

    public function query($sql, $params = []) {
        return $this->db()->query($sql, $params);
    }

    public function count($where = []) {
        $ret = $this->find($where, 'count(1) num');
        return $ret['num'];
    }

    public function dataTable(array $where = [], $cols = '*', array $order = []) {
        $sql = "select {$cols} from {$this->table} where ";
        $count_sql = "select count(1) num from {$this->table} where ";

        $start = Request::request('start', 0);
        $length = Request::request('length', 10);
        $sql_arr = $this->db()->toSql($where, $order, [$start, $length]);
        $content_sql_arr = $this->db()->toSql($where, $order);

        $sql .= $sql_arr['sql'];
        $count_sql .= $content_sql_arr['sql'];

        $count = $this->db()->query($count_sql, $content_sql_arr['params']);
        $count = $count[0]['num'];

        $data['data'] = $this->db()->query($sql, $sql_arr['params']);
        $data['draw'] = Request::request('draw');
        $data['iTotalRecords'] = $count;
        $data['iTotalDisplayRecords'] = $count;

        return $data;
    }

}