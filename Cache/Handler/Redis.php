<?php
namespace Cache\Handler;

use Base\Exception as Exception;

/**
 * 未实现sharding
 * Class \S\Cache\Redis
 */
class Redis extends Abstraction {

    const   DEFAULT_CONNECT_TIMEOUT = 1;
    const   DEFAULT_SEND_TIMEOUT = 1;
    const   DEFAULT_RECV_TIMEOUT = 1;

    protected $livetime = 0;

    /**
     * @var \Redis
     */
    protected $redis = null;

    /**
     * 用于判断是否使用持久连接，如果使用，析构函数中不做close操作
     *
     * @var bool
     */
    private $_persistent = false;

    /**
     * get
     *
     * @param string $key key值
     *
     * @return mixed
     */
    public function get($key) {
        $ret = $this->getInstance()->get($key);

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
            $ret = $this->getInstance()->set($key, $value);
        } else {
            $ret = $this->getInstance()->setex($key, $expire, $value);
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
        $ret = $this->getInstance()->del($key);

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
        $ret = $this->getInstance()->mget($keys);

        return $ret;
    }

    /**
     * 去掉危险操作的功能
     *
     * @return bool
     */
    public function flush() {
        return false;
    }

    public function close() {
        $this->redis->close();

        return true;
    }

    /**
     * 将其他方法调用均转向到封装的Redis实例
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($name, $args = array()) {
        $ret = call_user_func_array(array($this->getInstance(), $name), $args);

        return $ret;
    }

    public function __destruct() {
        if (!$this->_persistent) {
            $this->close();
        }
    }

    /**
     * @return \Redis
     * @throws \Base\Exception
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
     * @throws \Base\Exception
     */
    protected function connect() {
        if (isset($this->config['persistent']) && $this->config['persistent']) {
            $this->_persistent = true;
            $conn              = $this->redis->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
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