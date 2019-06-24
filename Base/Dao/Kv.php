<?php
namespace Base\Dao;

use Base\Exception as Exception;

class Kv {
    public $name = 'common';

    ///定义支持的操作
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

        $cache_id = strtolower(substr($name, strlen($function)));
        if (empty($this->$cache_id)) {
            throw new Exception("$cache_id not configured");
        }

        $kv = new \S\Db\Redis($this->name);

        if ('get' == $function) {
            $key = $this->getKey($cache_id, $arguments[0]);
            $ret = $kv->get($key);
            $ret = $ret ? json_decode($ret, true) : false;
        } else if ('set' == $function) {
            $key = $this->getKey($cache_id, $arguments[0]);
            $data = json_encode($arguments[1]);
            $ret = $kv->set($key, $data) ? true : false;
        } else if ('del' == $function) {
            $key = $this->getKey($cache_id, $arguments[0]);
            $ret = $kv->del($key) ? true : false;
        } else if ("mget" == $function) {
            $keys = $this->getKey($cache_id, $arguments[0]);
            $vals = $kv->mget($keys);
            $ret = array();
            foreach ($keys as $idx => $key) {
                $val = $vals[$idx];
                $ret[$key] = ($val ? json_decode($val, true) : $val);
            }
        } else if ('mset' == $function) {
            $vals = array();
            foreach ($arguments[0] as $key => $val) {
                $vals[$this->getKey($cache_id, $key)] = json_encode($val);
            }
            $ret = $kv->mset($vals) ? true : false;
        } else if ('mdel' == $function) {
            $keys = $this->getKey($cache_id, $arguments[0]);
            $ret = $kv->mdel($keys) ? true : false;
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

        if (is_array($key)) {
            foreach ($key as &$item) {
                $item = $prefix . '_' . $item;
            }

            return $key;
        }

        return 'KV' . $prefix . '_' . $key;
    }

    /**
     * 设置缓存配置
     *
     * @param string $cache_id 缓存标识
     * @param string $prefix 键前缀
     */
    protected function setConfig($cache_id, $prefix) {
        $cache_id = strtolower($cache_id);
        $this->$cache_id = $prefix;
    }
}