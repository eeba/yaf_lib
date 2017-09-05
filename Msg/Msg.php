<?php
namespace Msg;

class Msg {


    const SEND_TYPE_MAIL = "local";
    const SEND_TYPE_FT = "ft";

    private static $instance = null;

    /**
     * 获取存储驱动实例
     * @param string $type   类型(cdn, filesystem)
     * @param string $config 类型(cdn, filesystem)
     * @return object Handler
     */
    public static function getInstance($type = self::SEND_TYPE_FT, $config = 'default') {
        if (self::$instance === null) {
            $handler = __NAMESPACE__."\\Handler\\".ucfirst($type);
            self::$instance = new $handler($config);
        }
        return self::$instance;
    }



}