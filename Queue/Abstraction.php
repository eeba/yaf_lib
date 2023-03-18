<?php

namespace Queue;

abstract class Abstraction
{
    /**
     * 队列的入队操作
     * @param string $queue_name
     * @param string $value
     * @return mixed
     */
    abstract function push(string $queue_name, string $value): bool;

    /**
     * 队列的出队操作
     * @param string $queue_name
     * @return mixed
     */
    abstract function pop(string $queue_name): array;

    /**
     * 队列的长度
     * @param $queue_name
     * @return mixed
     */
    abstract function len($queue_name): int;
}