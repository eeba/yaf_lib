<?php

namespace Security;

use Base\Dao\Redis;

/**
 * Class Sfreq
 * 频率限制器
 *
 * demo
 * 假设我们针对ip为10.0.0.1 进行频率限制 限制次数为1小时5次
 * $freq = new Freq();
 *
 * //检查是否到达上限
 * @return true 未到上限  false 已到上限
 * $ret = $freq->check('ip_limit','10.0.0.1',5,3600);
 *
 * //加一
 * $ret = $freq->add('ip_limit','10.0.0.1',5,3600);
 *
 * //获取信息
 * $info = $freq->get('ip_limit','10.0.0.1');
 *
 * //清理数据
 * $ret = $freq->clear('ip_limit','10.0.0.1');
 *
 */
class Freq
{
    const TTL_TYPE_DAY = 1;  //按自然日计数
    const TTL_TYPE_WEEK = 2;  //按自然周计数
    const TTL_TYPE_MONTH = 3;  //按自然月计数
    const TTL_TYPE_YEAR = 4;  //按自然年计数
    const TTL_TYPE_FOREVER = 5;  //永久有效

    private static Redis $_redis;

    public function __construct($name = Redis::NAME_DEFAULT)
    {
        if (!self::$_redis) {
            self::$_redis = new Redis($name);
        }
        return self::$_redis;
    }

    /**
     * 获取信息
     *
     * @param $rule_name
     * @param $key
     * @return int
     */
    public function get($rule_name, $key): int
    {
        return (int)self::$_redis->get($rule_name . ':' . $key);
    }

    /**
     * 清理数据
     *
     * @param $rule_name
     * @param $key
     * @return bool
     */
    public function clear($rule_name, $key): bool
    {
        return self::$_redis->del($rule_name . ':' . $key);
    }

    /**
     * 检查是否到达上限
     *
     * @param $rule_name
     * @param $key
     * @param $threshold
     * @return bool
     */
    public function check($rule_name, $key, $threshold): bool
    {
        return $threshold > $this->get($rule_name, $key);
    }

    /**
     * 只累计计数
     *
     * @param $rule_name
     * @param $key
     * @param $ttl
     * @param $increment
     * @return int
     */
    public function incr($rule_name, $key, $ttl, $increment): int
    {
        $result = self::$_redis->incrBy($rule_name . ':' . $key, $increment);
        if (self::$_redis->ttl($key) < 0) {
            self::$_redis->expire($key, $ttl);
        }

        return $result;
    }

    /**
     * 先判断后计数
     *
     * @param $rule_name
     * @param $key
     * @param $threshold
     * @param $ttl
     * @param int $increment
     * @return false|int
     */
    public function add($rule_name, $key, $threshold, $ttl, int $increment = 1): bool|int
    {
        if (!$this->check($rule_name, $key, $threshold)) {
            return false;
        }

        return $this->incr($rule_name, $key, $ttl, $increment);
    }


    /**
     * 按自然年月日限制
     *
     * @param string $rule_name 规则名
     * @param string $key 唯一标示
     * @param int $threshold 阈值
     * @param int $freqDesc 自然周期 0-每自然日 1-每自然周 2-每自然月 3-每自然年
     * @param int $increment default 1 单次增加频率数量
     *
     * @return bool
     */
    public function addByNaturalTime(string $rule_name, string $key, int $threshold, int $freqDesc, int $increment = 1):bool
    {
        return $this->add($rule_name, $key, $threshold, self::getTTL($freqDesc), $increment);
    }

    /**
     * 根据频率描述符获取对应过期时间
     *
     * @param $freqDesc 1-每自然日 2-每自然周 3-每自然月 4-每自然年
     * @return int
     */
    public static function getTTL($freqDesc): int
    {
        $timestamp = time();
        return match ($freqDesc) {
            self::TTL_TYPE_DAY => strtotime(date("Ymd000000", strtotime("next Day", $timestamp))) - $timestamp,
            self::TTL_TYPE_WEEK => strtotime(date("Ymd000000", strtotime("next Week", $timestamp))) - $timestamp,
            self::TTL_TYPE_MONTH => strtotime(date("Ym01000000", strtotime("next Month", $timestamp))) - $timestamp,
            self::TTL_TYPE_YEAR => strtotime(date("Y0101000000", strtotime("next Year", $timestamp))) - $timestamp,
            default => 0,
        };
    }

}