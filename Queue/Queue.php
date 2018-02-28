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
    const TYPE_DEFAULT = self::TYPE_REDIS;

    const NAME_COMMON = 'common';

    public static function __callStatic($name, $args) {
        $queue = self::pool(self::TYPE_DEFAULT, self::NAME_COMMON);
        return call_user_func_array(array($queue, $name), $args);
    }

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
    public static function pool($type, $name = '') {
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