<?php

namespace Queue;

use Base\Dao\Redis as DataRedis;

class Redis extends Abstraction
{
    /**
     * @var \Redis
     */
    protected $redis = null;

    public function __construct($config = '')
    {
        $this->redis = $config ? new DataRedis($config) : new DataRedis();
    }

    public function push($queue_name, $value, array $option = array())
    {
        return $this->redis->rPush($this->getKey($queue_name), $value);
    }

    public function pop($queue_name, array $option = array())
    {
        return $this->redis->lPop($this->getKey($queue_name));
    }

    public function len($queue_name)
    {
        return $this->redis->lLen($this->getKey($queue_name));
    }

    public function getKey($key): string
    {
        return 'QUEUE_' . strtoupper($key);
    }
}