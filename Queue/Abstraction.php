<?php
namespace Queue;

abstract class Abstraction {
    /**
     * 队列的入队操作
     * @param       $queue_name
     * @param       $value
     * @param array $option
     * @return mixed
     */
    abstract function push($queue_name, $value, $option = array());

    /**
     * 队列的出队操作
     * @param       $queue_name
     * @param array $option
     * @return mixed
     */
    abstract function pop($queue_name, $option = array());

    /**
     * 队列的长度
     * @param $queue_name
     * @return mixed
     */
    abstract function len($queue_name);
}