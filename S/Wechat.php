<?php
namespace S;

use EasyWeChat\Factory;

class WeChat
{
    protected static $apps = [];

    /**
     * @param string $account
     * @return mixed
     */
    public static function app($account = '') {
        if(!self::$apps[$account]) {
            $config = Config::get('server.wechat.' . $account);
            $config['http']['proxy'] = Config::get('proxy.default');
            self::$apps[$account] = Factory::officialAccount($config);
        }
        return self::$apps[$account];
    }


}