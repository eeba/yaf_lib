<?php
namespace Security;

use Log\Logger;

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
        unset($params['key'], $params['t'], $params['m']);
        ksort($params, SORT_STRING);
        Logger::debug('sign_data', [$params, $app_key.$app_secret.$time.implode('', $params)]);
        $sign = $app_key.$app_secret.$time.implode('', $params);
        return md5($sign);
    }
}