<?php

namespace Base;

/**
 * 只适用于Rewrite路由
 *
 * Class Route
 * @package Base
 */
class Route
{

    /**
     * 获取当前请求中的路由参数, 路由参数不是指$_GET或者$_POST, 而是在路由过程中, 路由协议根据Request Uri分析出的请求参数.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public static function getParam($name = '', $default = '')
    {
        return \Yaf_Application::app()->getDispatcher()->getRequest()->getParam($name, $default);
    }


    public static function getUrl()
    {
        $config = require CONF_PATH . '/route.php';
        var_dump($config);
    }

}