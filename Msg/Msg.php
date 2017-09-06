<?php
namespace Msg;

class Msg {

    const SEND_TYPE_MAIL = "mail";  //邮件消息
    const SEND_TYPE_FT = "ft";      //方糖 http://sc.ftqq.com/3.version

    private static $instance = null;

    /**
     * 获取存储驱动实例
     * @param string $type 类型(mail, ft)
     * @param string $config 发送账户(system, alert)
     * @return object Handler
     */
    public static function getInstance($type = self::SEND_TYPE_MAIL, $config = 'system') {
        if (self::$instance === null) {
            $handler = __NAMESPACE__ . "\\Handler\\" . ucfirst($type);
            self::$instance = new $handler($config);
        }
        return self::$instance;
    }

}