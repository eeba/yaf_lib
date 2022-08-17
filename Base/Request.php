<?php

namespace Base;

class Request extends \Yaf_Request_Http
{
    public static function request($name = '', $default = '')
    {
        $params_string = strval(file_get_contents("php://input"));
        $data = json_decode($params_string, true);
        $data = is_array($data) ? $data : [];
        $data = array_merge($data, $_REQUEST); // 优先使用post参数
        if ($name) {
            return $data[$name] ?? $default;
        }
        return $data;
    }

    public function isAjax()
    {
        parent::isXmlHttpRequest();
    }

    public function isHttps()
    {
        if ($this->getServer('HTTPS') === 'on'
            || $this->getServer('HTTP_X_FORWARDED_PROTO') === 'https'//为解决教育网https登录的问题，由教育网vip添加一个http头信息
            || $this->getServer('HTTP_X_PROTO') === 'SSL'//当https证书放在负载均衡上时，后端server通过HTTP头来判断前端的访问方式
        ) {
            return true;
        }
        return false;
    }

    public function getRequestDataTime()
    {
        return date('Y-m-d H:i:s', $this->getServer('REQUEST_TIME'));
    }
}