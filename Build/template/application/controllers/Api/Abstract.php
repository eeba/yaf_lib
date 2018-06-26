<?php

use Http\Request;
use Base\Exception;

abstract class Controller_Api_Abstract extends Common\ApiAbstract {

    public $app_key;
    public $app_id;

    public function before() {
        //访问频率限制. 同一接口，同一ip 每分钟最多100次
        $key = Base\Env::$controller . '_' . ip2long(Util\Ip::getClientIp());
        $ret = (new Security\Freq())->add('ACCESS_TIMES', $key, 100, 60);
        if (!$ret) {
            throw new Exception('The frequency of access is too fast', 9999999);
        }

        //客户端限制
        //$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        //$isWeiXin = strpos($ua, 'micromessenger') === false ? false : true;
        //$isAndroid = strpos($ua, 'android') === false ? false : true;
        //$isIos = strpos($ua, 'iphone') === false ? false : true;

    }

    /**
     * 接口权限验证
     *
     * @throws Exception
     */
    public function _auth() {
        //验签参数判断
        if (!Request::get("app_key") || !Request::get('m') || !Request::get('t')) {
            throw new Exception("msg.system.illegal_request");
        }

        //验签参数超时
        if (time() - Request::get('t') > 600) {
            throw new Exception("msg.system.request_expired");
        }

        //app_key验证
        $this->app_key = Request::get("app_key");
        if (!preg_match('/^[a-zA-Z0-9_]{1,32}$/', $this->app_key)) {
            throw new Exception('validate.illegal_app_key');
        }

        $app_info = (new \Data\App\Base())->getInfoByAppKey($this->app_key);
        $this->app_id = $app_info['id'];
        if (!$this->app_id) {
            throw new Exception('error.api.illegal_app_key');
        }

        //签名验证
        $method = strtoupper(Request::getHttpMethod());
        if ($method == 'GET') {
            unset($_GET['app_key'], $_GET['m'], $_GET['t']);
            $param = $_GET;
        } else {
            $param = $_POST;
        }
        ksort($param);
        $encode_param = implode("", $param);
        $sign = false;
        foreach ($app_info['secret'] as $app_secret) {
            if (Request::get('m') == md5($this->app_key . Request::get('t') . $app_secret . $encode_param)) {
                $sign = true;
            }
        }
        if ($sign) {
            throw new Exception('error.api.signature_verification_failed');
        }

        //接口权限验证
        if (!in_array(get_class($this), $app_info['api'])) {
            throw new Exception('error.api.unauthorized_access');
        }
    }
}