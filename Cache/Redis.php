<?php

namespace Cache;

use Base\Exception;

/**
 * Class \Cache\Redis
 */
class Redis extends Abstraction
{
    protected $redis = null;


    /**
     * @param $key
     *
     * @return bool|string
     * @throws Exception
     */
    public function get($key)
    {
        $ret = $this->getInstance()->get($key);

        return $ret;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expire
     *
     * @return bool
     * @throws \Base\Exception
     */
    public function set($key, $value, $expire = 60)
    {
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
     * @return bool|int
     */
    public function del($key)
    {
        return $this->getInstance()->del($key);
    }

    /**
     * 实现mget接口
     *
     * @param array $keys 包含key值的数组
     *
     * @return array|false
     * @throws Exception
     */
    public function mget(array $keys)
    {
        $ret = $this->getInstance()->mget($keys);

        return $ret;
    }

    public function close()
    {
        $this->getInstance()->close();

        return true;
    }

    public function getInstance()
    {
        if (!$this->redis) {
            $this->redis = new \Base\Dao\Redis('server.redis.cache');
        }
        return $this->redis;
    }
}