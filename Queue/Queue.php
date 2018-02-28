<?php

namespace Queue;

/**
 * 封装队列及简易调用
 *
 * 配置文件的解析放在相应的文件中，初始化时只提供配置名
 * 配置文件为conf/server/../queue.php
 *
 *
 * <code>
 * $ret = \Queue\Queue::push($key, $value); //默认使用\Queue\Queue::pool('redis', 'common')配置
 * $ret = \Queue\Queue::pop($key);
 *
 * //init
 * $queue  = \Queue\Queue::pool('redis', 'common');
 *
 * //push
 * $queue->push('key1', '1234567890');
 *
 * //pop
 * $queue->pop('key1');
 *
 * </code>
 */

class Queue {
    const TYPE_REDIS = 'redis';

    const DEFAULT_TYPE = self::TYPE_REDIS;
    const DEFAULT_NAME = 'common';

    /**
     * @var \Queue\Abstraction[]
     */
    private static $pools = array();

    /**
     * 根据名字获取一个队列实例
     *
     * @param string $type 队列类型
     * @param string $name 实例名
     * @return \Queue\Abstraction
     */
    public static function pool($type = self::DEFAULT_TYPE, $name = self::DEFAULT_NAME) {
        $key = self::getKey($type, $name);

        if (!isset(self::$pools[$key])) {
            $handler_ns = __NAMESPACE__ . '\\Handler\\';

            $obj = new ($handler_ns . ucfirst($type))();
            self::$pools[$key] = $obj;
        }
        return self::$pools[$key];
    }

    private static function getKey($type, $name = '') {
        return $type . '-' . $name;
    }
}