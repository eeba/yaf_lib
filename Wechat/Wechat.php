<?php
namespace Wechat;

use EasyWeChat\Factory;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class WeChat
{
    protected static $apps = [];

    /**
     * @param string $account
     * @return mixed
     */
    public static function app($account = '') {
        if(!self::$apps[$account]) {
            $config = \Base\Config::get('server.wechat.' . $account);

            //添加代理
            $config['http']['proxy'] = \Base\Config::get('server.proxy.default');
            //关闭ssl验证
            $config['http']['verify'] = false;
            $app = Factory::officialAccount($config);

            //替换应用中的缓存为redis
            $redis = (new \Base\Dao\Redis())->getInstance();
            $cache = new RedisAdapter($redis);
            $app->rebind('cache', $cache);
            self::$apps[$account] = $app;
        }
        return self::$apps[$account];
    }


}