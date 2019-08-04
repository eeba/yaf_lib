<?php
namespace S\Security;

use Base\Logger;

class Sign {
    /**
     * 生成请求时所需的签名串
     *
     * @param       $app_key
     * @param       $app_secret
     * @param       $time
     * @param array $params
     *
     * @return string
     */
    public static function getSign($app_key, $app_secret, $time, array $params = array()){
        unset($params['app_key'], $params['app_secret'], $params['t'], $params['m']);
        ksort($params, SORT_STRING);
        Logger::getInstance()->debug(['sign_data' => $params]);
        $sign = $app_key.$app_secret.$time.implode('', $params);
        return md5($sign);
    }
}