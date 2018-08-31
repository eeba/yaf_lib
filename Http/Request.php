<?php
namespace Http;

class Request {

    /**
     * 从各种全局变量中取出信息
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public static function server($name, $default = '') {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }

    /**
     * 从$_GET中获取信息
     *
     * @param string $name 键
     * @param string $default '' 当前键对应的信息不存在时的默认返回值
     *
     * @return string
     */
    public static function get($name, $default = '') {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    /**
     * 从$_POST中获取信息
     *
     * @param string $name 键
     * @param string $default '' 当前键对应的信息不存在时的默认返回值
     *
     * @return string
     */
    public static function post($name, $default = '') {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    /**
     * 从$_REQUEST中获取信息
     *
     * @param string $name 键
     * @param string $default '' 当前键对应的信息不存在时的默认返回值
     *
     * @return string
     */
    public static function request($name, $default = '') {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * 从$_COOKIE中获取信息
     *
     * @param string $name 键
     * @param string $default '' 当前键对应的信息不存在时的默认返回值
     *
     * @return string
     */
    public static function cookie($name, $default = '') {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    /**
     * 从$_SESSION中获取信息
     *
     * @param string $name 键
     * @param string $default '' 当前键对应的信息不存在时的默认返回值
     *
     * @return string
     */
    public static function session($name, $default = '') {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }

    /**
     * 获取指定键值的http header
     *
     * @param string $name 标准http header名称（Content-Type, Content-Length .etc）或自定义header，不区分大小写
     * @param string $default ''
     *
     * @return string
     */
    public static function header($name, $default = '') {
        if ($name == "Content-Type") {
            return $_SERVER["CONTENT_TYPE"];
        }
        if ($name == "Content-Length") {
            return $_SERVER["CONTENT_LENGTH"];
        }

        $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));

        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }

    /**
     * 获取http请求方法。
     * @return string GET/POST/PUT/DELETE/HEAD等
     */
    public static function getHttpMethod() {
        return self::server('REQUEST_METHOD');
    }

    /**
     * 判断当前请求是否是XMLHttpRequest(AJAX)发起
     * @return boolean
     */
    public static function isAjax() {
        return (self::server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') ? true : false;
    }

    /**
     * 检查当前请求是否是https
     * @return bool
     */
    public static function isHttps() {
        if (self::server('HTTPS') === 'on'
            || self::server('HTTP_X_FORWARDED_PROTO') === 'https'//为解决教育网https登录的问题，由教育网vip添加一个http头信息
            || self::server('HTTP_X_PROTO') === 'SSL'//当https证书放在负载均衡上时，后端server通过HTTP头来判断前端的访问方式
        ) {
            return true;
        }
        return false;
    }

    /**
     * 保障时间的统一
     * @return mixed
     */
    public static function getRequestTimestamp() {
        return self::server('REQUEST_TIME');
    }

    public static function getRequestDataTime() {
        static $datatime;
        if ($datatime === null) {
            $datatime = date('Y-m-d H:i:s', self::server('REQUEST_TIME'));
        }
        return $datatime;
    }

    /**
     * 获取当前域名地址
     * @return string
     */
    public static function getHost(){
        $scheme = self::server('HTTPS') === 'on' ? 'https://' : 'http://';
        $port = self::server('SERVER_PORT') != '80' ? ':' . self::server('SERVER_PORT') : '';
        return $scheme . self::server('HTTP_HOST') . $port;
    }

    /**
     * 获取当前请求地址
     * @return string
     */
    public static function getCurrentUrl() {
        return self::getHost() . self::server('REQUEST_URI');
    }
}