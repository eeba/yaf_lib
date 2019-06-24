<?php
namespace S\Cache\Handler;

use Base\Exception;

/**
 * Class \Cache\Redis
 */
class Redis extends Abstraction {
    protected $redis = null;

    /**
     * get
     *
     * @param string $key key值
     *
     * @return mixed
     */
    public function get($key) {
        $ret = $this->redis->get($key);

        return $ret;
    }

    /**
     * set
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $expire
     *
     * @return bool
     * @throws \Base\Exception
     */
    public function set($key, $value, $expire = 60) {
        if ($expire == 0) {
            $ret = $this->redis->set($key, $value);
        } else {
            $ret = $this->redis->setex($key, $expire, $value);
        }

        return $ret;
    }

    /**
     * 实现del接口
     *
     * @param string $key key值
     *
     * @return bool
     */
    public function del($key) {
        $ret = $this->redis->del($key);

        return $ret;
    }

    /**
     * 实现mget接口
     *
     * @param array $keys 包含key值的数组
     *
     * @return array
     */
    public function mget(array $keys) {
        $ret = $this->redis->mget($keys);

        return $ret;
    }

    public function close() {
        $this->redis->close();

        return true;
    }

    /**
     * @return \Redis|null
     * @throws Exception
     */
    public function getInstance() {
        if (!$this->redis) {
            $this->redis = new \Redis();
            $this->connect();
            $this->setOptions();
        }

        return $this->redis;
    }

    /**
     * 初始化配置信息，以addServers方式使用
     *
     * @return bool
     * @throws Exception
     */
    protected function connect() {
        $config = $this->config[$this->name];
        if (isset($config['persistent']) && $config['persistent']) {
            $this->_persistent = true;
            $conn = $this->redis->pconnect($config['host'], $config['port'], $config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if ($conn === false) {
                $conn = $this->redis->pconnect($config['host'], $config['port'], $config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        } else {
            $conn = $this->redis->connect($config['host'], $config['port'], $config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if ($conn === false) {
                $conn = $this->redis->connect($config['host'], $config['port'], $config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        }

        if ($conn === false) {
            throw new Exception("redis connect " . $config['host'] . " fail");
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected function setOptions() {
        $config = $this->config[$this->name];
        if (isset($config['user']) && $config['user'] && $config['auth']) {
            if ($this->redis->auth($config['user'] . ":" . $config['auth']) == false) {
                throw new Exception("redis auth " . $config['host'] . " fail");
            }
        }
        if (isset($config['db']) && $config['db']) {
            $this->redis->select($config['db']);
        }
    }
}