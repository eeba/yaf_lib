<?php
namespace Base\Dao;

use Base\Exception as Exception;

class Cache {

    const DEFAULT_TTL = 86400;  //默认缓存对象过期时间: 1day=3600s * 24

    ///定义支持的缓存操作
    protected static $_functions = array('get', 'set', 'del', 'mget', 'mset', 'mdel');

    protected $pool_type = \S\Cache\Cache::TYPE_DEFAULT;  //缓存类型, 参考\S\Cache\Cache缓存类型常量定义
    protected $pool_name = \S\Cache\Cache::NAME_DEFAULT; //缓存配置名称, 参考\S\Cache\Cache常量定义

    /**
     * @param $name
     * @param $arguments
     *
     * @return array|bool|mixed
     * @throws Exception
     */
    public function __call($name, $arguments) {
        $function = null;
        foreach (self::$_functions as $need_function) {
            if (0 === stripos($name, $need_function)) {
                $function = $need_function;
            }
        }
        if (!$function) {
            throw new Exception("unsupported function: $function");
        }

        $cache_id = strtolower(substr($name, strlen($function)));
        if (empty($this->$cache_id)) {
            throw new Exception("$cache_id not configured");
        }
        if (!($this->pool_name && $this->pool_type)) {
            throw new Exception("class need set pool_name and pool_type");
        }

        $cache = \S\Cache\Cache::pool($this->pool_type, $this->pool_name);

        if ('get' == $function) {

            $key = $this->getKey($cache_id, $arguments[0]);
            $ret = $cache->get($key);
            $ret = $ret ? json_decode($ret, true) : false;
        } else if ('set' == $function) {

            $config = $this->$cache_id;
            $key = $this->getKey($cache_id, $arguments[0]);
            $data = json_encode($arguments[1]);
            $ret = $cache->set($key, $data, $config['ttl']) ? true : false;
        } else if ('del' == $function) {

            $key = $this->getKey($cache_id, $arguments[0]);
            $ret = $cache->del($key) ? true : false;
        } else if ("mget" == $function) {

            $keys = $this->getKey($cache_id, $arguments[0]);
            $vals = $cache->mget($keys);

            $ret = array();
            foreach ($keys as $idx => $key) {
                $val = $vals[$idx];
                $ret[$key] = ($val ? json_decode($val, true) : $val);
            }
        } else if ('mset' == $function) {

            $config = $this->$cache_id;
            $vals = array();
            foreach ($arguments[0] as $key => $val) {
                $vals[$this->getKey($cache_id, $key)] = json_encode($val);
            }
            $ret = $cache->mset($vals, $config['ttl']) ? true : false;
        } else if ('mdel' == $function) {

            $keys = $this->getKey($cache_id, $arguments[0]);
            $ret = $cache->mdel($keys) ? true : false;
        } else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * 获取键
     *
     * @param string       $cache_id 缓存标识
     * @param string|array $key 键
     *
     * @return string|array 添加前缀后的键
     */
    protected function getKey($cache_id, $key) {
        $prefix = $this->$cache_id;
        $prefix = $prefix['prefix'];

        if (is_array($key)) {
            foreach ($key as &$item) {
                $item = $prefix . '_' . $item;
            }

            return $key;
        }

        return 'CACHE_' . $prefix . '_' . $key;
    }

    /**
     * 设置缓存配置
     *
     * @param string $cache_id 缓存标识
     * @param string $prefix 键前缀
     * @param int    $ttl default 86400 缓存时效, 默认1天
     */
    protected function setConfig($cache_id, $prefix, $ttl = self::DEFAULT_TTL) {
        $cache_id = strtolower($cache_id);
        $this->$cache_id = array(
            'prefix' => $prefix,
            'ttl' => $ttl,
        );
    }

}