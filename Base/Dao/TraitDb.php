<?php

namespace Base\Dao;

use Base\Request;
use Exception;

trait TraitDb
{

    /**
     * 事务开始
     */
    public function begin(): void
    {
        self::db()->begin();
    }

    /**
     * 提交事务
     */
    public function commit(): void
    {
        self::db()->commit();
    }

    /**
     * 回滚
     */
    public function rollback(): void
    {
        self::db()->rollback();
    }

    /**
     * 添加
     *
     * @param $data
     * @return bool|int|null
     * @throws Exception
     */
    public function add($data): bool|int|null
    {
        return self::db()->insert($this->table, $data);
    }

    /**
     * 更新数据
     *
     * @param array $data
     * @param array $where
     * @param array $order
     * @param int $limit
     * @return bool|int|null
     */
    public function update(array $data = array(), array $where = array(), array $order = array(), $limit = 0): bool|int|null
    {
        return self::db()->update($this->table, $data, $where, $order, $limit);
    }

    /**
     * 根据主键id，查一条数据
     *
     * @param        $id
     * @param string $cols
     * @param array $order
     * @return array
     */
    public function findById($id, string $cols = '*', array $order = array()): array
    {
        return self::db()->find($this->table, ['id' => $id], $cols, $order);
    }

    /**
     * 查一条数据
     *
     * @param array $where
     * @param string $cols
     * @param array $order
     * @return array
     */
    public function find(array $where = [], string $cols = '*', array $order = array()): array
    {
        return self::db()->find($this->table, $where, $cols, $order);
    }

    /**
     * 查多条数据
     *
     * @param array $where
     * @param string $cols
     * @param array $order
     * @param int $limit
     * @return bool|int|array|null
     */
    public function getList(array $where = array(), string $cols = '*', array $order = array(), int $limit = 0): int|bool|array|null
    {
        return self::db()->select($this->table, $where, $cols, $order, $limit);
    }

    /**
     * 删除
     *
     * @param array $where
     * @param array $order
     * @param int $limit
     * @return bool|int|null
     */
    public function delete(array $where = array(), array $order = array(), int $limit = 0): bool|int|null
    {
        return self::db()->delete($this->table, $where, $order, $limit);
    }

    /**
     * 直接执行sql
     *
     * @param       $sql
     * @param array $params
     * @return bool|int|array|null
     */
    public function query($sql, array $params = []): int|bool|array|null
    {
        return self::db()->query($sql, $params);
    }

    /**
     * 统计数量
     *
     * @param array $where
     * @return mixed
     */
    public function count(array $where = []): mixed
    {
        $ret = $this->find($where, 'count(1) num');
        return $ret['num'];
    }

    /**
     * 和JQuery DataTables插件配合使用
     *
     * @param array $where
     * @param string $cols
     * @param array $order
     * @return array
     */
    public function dataTable(array $where = [], string $cols = '*', array $order = []): array
    {
        if (is_array($cols) && !empty($cols)) {
            $cols = implode(',', array_map(function ($v) {
                return "`{$v}`";
            }, array_values($cols)));
        } elseif (!$cols) {
            $cols = '*';
        }

        $sql = "select {$cols} from {$this->table} where ";
        $count_sql = "select count(1) num from {$this->table} where ";

        $request = new Request();
        $start = $request->getRequest('start', 0);
        $length = $request->getRequest('length', 10);
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