<?php

namespace Wechat;

use Base\Exception;
use EasyWeChat\Factory;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class Wechat
{
    protected static $apps = [];

    /**
     * @param string $account
     * @return mixed
     * @throws Exception
     */
    public static function app(string $account = '')
    {
        if (!isset(self::$apps[$account]) || !self::$apps[$account]) {
            $config = \Base\Config::get('server.wechat.' . $account);

            //添加代理
            $config['http']['proxy'] = \Base\Config::get('server.proxy.default');
            //关闭ssl验证
            $config['http']['verify'] = false;
            switch ($config['account_type']) {
                case "official":
                    $app = Factory::officialAccount($config);
                    break;
                case "mini":
                    $app = Factory::miniProgram($config);
                    break;
                default:
                    throw new Exception("公众号，小程序的配置项`account_type`错误");
            }

            //替换应用中的缓存为redis
            $redis = (new \Base\Dao\Redis())->getInstance();
            $cache = new RedisAdapter($redis);
            $app->rebind('cache', $cache);
            self::$apps[$account] = $app;
        }
        return self::$apps[$account];
    }

}