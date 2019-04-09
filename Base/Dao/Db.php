<?php
namespace Base\Dao;

class Db {

    protected static $db = array();
    protected $table = '';
    protected $field = [];
    protected static $db_name = '';

    public static function getInstance($db_name) {
        if (!isset(self::$db[$db_name])) {
            self::$db[$db_name] = new \S\Db\Mysql($db_name);
            //读写分离
            self::$db[$db_name]->setModeFlag(true);
        }
        return self::$db[$db_name];
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
}