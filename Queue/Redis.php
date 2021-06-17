<?php
namespace Queue;

use Base\Dao\Redis as DataRedis;

class Redis extends Abstraction {
    /**
     * @var \Redis
     */
    protected $redis = null;

    public function __construct($config = '') {
        $this->redis = $config ? new DataRedis($config) : new DataRedis();
    }

    public function push($queue_name, $message, $option = array()) {
        $ret = $this->redis->rPush($this->getKey($queue_name), $message);
        return $ret;
    }

    public function pop($queue_name, $option = array()) {
        $ret = $this->redis->lPop($this->getKey($queue_name));
        return $ret;
    }

    public function len($queue_name) {
        $ret = $this->redis->lLen($this->getKey($queue_name));
        return $ret;
    }

    public function getKey($key){
        return 'QUEUE_' . strtoupper($key);
    }
}