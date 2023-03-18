<?php

namespace Base\Dao;

use Base\Exception as Exception;
use Cache\Abstraction;

class Cache
{

    protected static Abstraction $cache;

    //默认缓存对象过期时间: 1day=3600s * 24
    const DEFAULT_TTL = 86400;

    //缓存类型，支持redis和yac
    protected string $cache_type = 'Redis';

    ///定义支持的缓存操作
    protected static array $_functions = array('get', 'set', 'del');

    /**
     * @param $name
     * @param $arguments
     *
     * @return array|bool|mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $function = null;
        foreach (self::$_functions as $need_function) {
            if (0 === stripos($name, $need_function)) {
                $function = $need_function;
            }
        }
        if (!$function) {
            throw new Exception("unsupported function: $function");
        }

        $cache_type = ucfirst($this->cache_type);
        if (!in_array($cache_type, ['Redis', 'Yac', 'Ipc'])) {
            throw new Exception("unsupported cache type: " . $this->cache_type);
        }

        $cache_id = strtolower(substr($name, strlen($function)));
        if (empty($this->$cache_id)) {
            throw new Exception("$cache_id not configured");
        }

        if (!self::$cache[$cache_type]) {
            $cache_class_name = '\\Cache\\' . $cache_type;
            self::$cache[$cache_type] = new $cache_class_name;
        }
        $cache = self::$cache[$cache_type];

        $key = $this->getKey($cache_id, $arguments[0]);
        if ('get' == $function) {
            $ret = $cache->get($key);
            $ret = $ret ? json_decode($ret, true) : false;
        } else if ('set' == $function) {
            $config = $this->$cache_id;
            $data = json_encode($arguments[1]);
            $ret = (bool)$cache->set($key, $data, $config['ttl']);
        } else if ('del' == $function) {
            $ret = (bool)$cache->del($key);
        }  else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * 获取键
     *
     * @param string $cache_id 缓存标识
     * @param array|string $key 键
     *
     * @return string|array 添加前缀后的键
     */
    protected function getKey(string $cache_id, array|string $key): array|string
    {
        $prefix = $this->$cache_id;
        $prefix = $prefix['prefix'];

        if (is_array($key)) {
            foreach ($key as &$item) {
                $item = $prefix . '_' . $item;
            }

            return $key;
        }

        return 'CACHE:' . $prefix . ':' . $key;
    }

    /**
     * 设置缓存配置
     *
     * @param string $cache_id 缓存标识
     * @param string $prefix 键前缀
     * @param int $ttl default 86400 缓存时效, 默认1天
     */
    protected function setConfig(string $cache_id, string $prefix, int $ttl = self::DEFAULT_TTL): void
    {
        $cache_id = strtolower($cache_id);
        $this->$cache_id = array(
            'prefix' => $prefix,
            'ttl' => $ttl,
        );
    }

}