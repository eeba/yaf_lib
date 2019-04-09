<?php
namespace S\Cache\Handler;

use S\Db\Redis as DbRedis;

/**
 * Class \Cache\Redis
 */
class Redis extends Abstraction {
    protected $redis = null;

    protected function init() {
        $this->redis = new DbRedis();
    }

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

    public function getInstance() {}
}