<?php
namespace Queue\Handler;

use Db\Redis as DbRedis;

class Redis extends Abstraction {
    /**
     * @var \Redis
     */
    protected $redis = null;

    public function __construct() {
        $this->redis = new DbRedis();
    }

    public function push($queue_name, $message, $option = array()) {
        $ret = $this->redis->rPush($queue_name, $message);
        return $ret;
    }

    public function pop($queue_name, $option = array()) {
        $ret = $this->redis->lPop($queue_name);
        return $ret;
    }

    public function len($queue_name) {
        $ret = $this->redis->lLen($queue_name);
        return $ret;
    }
}