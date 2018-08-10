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

    /**
     * 事务开始
     */
    public function begin() {
        $this->db()->begin();
    }

    /**
     * 提交事务
     */
    public function commit() {
        $this->db()->commit();
    }

    /**
     * 回滚
     */
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

    /**
     * 更新数据
     *
     * @param array $data
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     */
    public function update(array $data = array(), array $where = array(), array $order = array(), $limit = 0) {
        return $this->db()->update($this->table, $data, $where, $order, $limit);
    }

    /**
     * 根据主键id，查一条数据
     *
     * @param        $id
     * @param string $cols
     * @param array  $order
     * @return array
     */
    public function findById($id, $cols = '*', array $order = array()) {
        return $this->db()->find($this->table, ['id' => $id], $cols, $order);
    }

    /**
     * 查一条数据
     *
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @return array
     */
    public function find($where = [], $cols = '*', array $order = array()) {
        return $this->db()->find($this->table, $where, $cols, $order);
    }

    /**
     * 查多条数据
     *
     * @param array  $where
     * @param string $cols
     * @param array  $order
     * @param int    $limit
     * @return bool|int|null
     */
    public function getList(array $where = array(), $cols = '*', array $order = array(), $limit = 0) {
        return $this->db()->select($this->table, array_filter($where), $cols, $order, $limit);
    }

    /**
     * 删除
     *
     * @param array $where
     * @param array $order
     * @param int   $limit
     * @return bool|int|null
     */
    public function delete(array $where = array(), array $order = array(), $limit = 1){
        return $this->db()->delete($this->table, $where, $order, $limit);
    }

    /**
     * 直接执行sql
     *
     * @param       $sql
     * @param array $params
     * @return bool|int|null
     */
    public function query($sql, $params = []) {
        return $this->db()->query($sql, $params);
    }

    /**
     * 统计数量
     *
     * @param array $where
     * @return mixed
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
     */
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