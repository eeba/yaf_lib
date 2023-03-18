<?php

namespace Queue;

use Base\Dao\Redis as DataRedis;

class Redis extends Abstraction
{
    protected ?DataRedis $redis = null;

    public function __construct($config_name = '')
    {
        $this->redis = $config_name ? new DataRedis($config_name) : new DataRedis();
    }

    public function push(string $queue_name, string $value, array $option = array()): bool
    {
        return $this->redis->rPush($this->getKey($queue_name), $value);
    }

    public function pop($queue_name):array
    {
        $result = $this->redis->lPop($this->getKey($queue_name));
        if($result){
            return json_decode($result, true);
        }
        return [];
    }

    public function len($queue_name):int
    {
        return $this->redis->lLen($this->getKey($queue_name));
    }

    public function getKey($key): string
    {
        return 'QUEUE:' . strtoupper($key);
    }
}