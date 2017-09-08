<?php
namespace Cache;

/**
 * Class Cache
 *
 * @package     Cache
 * @description 封装缓存池和简易调用
 *
 * 配置文件的解析放在相应的文件中，初始化时只提供配置名
 * 如S\Cache\Cache::pool('memcache', 'userinfo');
 * 会到config下的memcache配置文件中查找userinfo项
 *
 * <code>
 * $ret = Cache\Cache::get($key);
 * $ret = Cache\Cache::set($key, $value);
 *
 * //init
 * $mc  = Cache\Cache::pool('memcache', 'userinfo');
 * $mcd = Cache\Cache::pool('memcached', 'userinfo');
 * $apc = Cache\Cache::pool('apc');
 *
 * //set
 * $mc->set('key1', 'hello,memcache', 3600);
 * $mcd->set('key1','hello,memcached', 3600);
 * $apc->set('key1','hello,file',3600);
 *
 * //get
 * $mc->get('key1');
 * $mcd->get('key1');
 * $apc->get('key1');
 *
 * </code>
 */
class Cache {

    const TYPE_MEMCACHE = 'memcache';
    const TYPE_MEMCACHED = 'memcached';
    const TYPE_REDIS = 'redis';
    const TYPE_YAC = 'yac';
    const TYPE_IPC = 'ipc';

    const TYPE_DEFAULT = self::TYPE_REDIS;
    const NAME_DEFAULT = 'common';

    private static $pools = array();

    public static function __callStatic($name, $args) {
        $cache = self::pool(self::TYPE_DEFAULT, self::NAME_DEFAULT);
        $ret = call_user_func_array(array($cache, $name), $args);

        return $ret;
    }

    /**
     * 根据名字获取一个缓存实例
     *
     * @param string $name 实例名
     * @param string $type 缓存类型
     *
     * @return \Cache\Handler\Abstraction   缓存实例
     * @throws \Exception
     */
    public static function pool($type, $name = '') {
        $key = self::getKey($type, $name);
        if (!isset(self::$pools[$key])) {
            $handler_ns = __NAMESPACE__ . '\\Handler\\';

            $class = $handler_ns . ucfirst($type);
            $instance = new $class();
            if (!is_subclass_of($instance, $handler_ns . 'CacheInterface')) {
                throw new \Exception($class . ' is not a subclass of \\Base\\Cache\\CacheInterface');
            }
            if (is_subclass_of($instance, $handler_ns . 'Abstraction')) {
                /* @var \Cache\Handler\Abstraction $instance */
                $instance->configure($type, $name);
            }
            self::$pools[$key] = $instance;
        }

        return self::$pools[$key];
    }

    public static function remove($type, $name = "") {
        $key = self::getKey($type, $name);
        if (isset(self::$pools[$key])) {
            self::$pools[$key]->close();
            unset(self::$pools[$key]);
        }

        return true;
    }

    private static function getKey($type, $name = '') {
        return $key = $type . '-' . $name;
    }

}