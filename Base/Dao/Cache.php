<?php
namespace Base\Dao;

use Base\Exception as Exception;

class Cache {

    protected static $cache;

    //默认缓存对象过期时间: 1day=3600s * 24
    const DEFAULT_TTL = 86400;

    //缓存类型，支持redis和yac
    protected $cache_type = 'Redis';

    ///定义支持的缓存操作
    protected static $_functions = array('get', 'set', 'del', 'mget', 'mset', 'mdel');

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

        $cache_type = ucfirst($this->cache_type);
        if(!in_array($cache_type, ['Redis', 'Yac', 'Ipc'])){
            throw new Exception("unsupported cache type: " . $this->cache_type);
        }

        $cache_id = strtolower(substr($name, strlen($function)));
        if (empty($this->$cache_id)) {
            throw new Exception("$cache_id not configured");
        }

        if(!self::$cache[$cache_type]){
            $cache_class_name = '\\Cache\\' . $cache_type;
            self::$cache[$cache_type] = new $cache_class_name;
        }
        $cache = self::$cache[$cache_type];

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