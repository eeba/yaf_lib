<?php
namespace Queue;

abstract class Abstraction {
    /**
     * 队列的入队操作
     * @param       $key
     * @param       $value
     * @param array $option
     * @return mixed
     */
    abstract function push($key, $value, $option = array());

    /**
     * 队列的出队操作
     * @param       $key
     * @param array $option
     * @return mixed
     */
    abstract function pop($key, $option = array());
}