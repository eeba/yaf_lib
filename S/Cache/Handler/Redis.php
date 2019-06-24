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
        if (isset($this->config['persistent']) && $this->config['persistent']) {
            $this->_persistent = true;
            $conn = $this->redis->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if ($conn === false) {
                $conn = $this->redis->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        } else {
            $conn = $this->redis->connect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if ($conn === false) {
                $conn = $this->redis->connect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        }

        if ($conn === false) {
            throw new Exception("redis connect " . $this->config['host'] . " fail");
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected function setOptions() {
        if (isset($this->config['user']) && $this->config['user'] && $this->config['auth']) {
            if ($this->redis->auth($this->config['user'] . ":" . $this->config['auth']) == false) {
                throw new Exception("redis auth " . $this->config['host'] . " fail");
            }
        }
        if (isset($this->config['db']) && $this->config['db']) {
            $this->redis->select($this->config['db']);
        }
    }
}