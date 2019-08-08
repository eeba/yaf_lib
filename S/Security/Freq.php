<?php
namespace S\Security;

use S\Data\Redis;
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
class Freq {
    const TTL_TYPE_DAY = 1;  //按自然日计数
    const TTL_TYPE_WEEK = 2;  //按自然周计数
    const TTL_TYPE_MONTH = 3;  //按自然月计数
    const TTL_TYPE_YEAR = 4;  //按自然年计数
    const TTL_TYPE_FOREVER = 5;  //永久有效

    const TIMEOUT = 3;
    /**
     * @var \Redis
     */
    private static $_redis;

    public function __construct($name = Redis::NAME_DEFAULT) {
        if (!self::$_redis) {
            self::$_redis = new Redis($name);
        }
        return self::$_redis;
    }

    public function get($rule_name, $key) {
        return self::$_redis->hGet($rule_name, $key);
    }

    public function clear($rule_name, $key) {
        return self::$_redis->hDel($rule_name, $key);
    }

    /**
     * @param string $rule_name 规则名
     * @param string $key 唯一标示
     * @param int    $threshold 阈值
     *
     * @return bool|string
     */
    public function check($rule_name, $key, $threshold) {
        $count = self::$_redis->hGet($rule_name, $key);
        $result = ($threshold > intval($count));

        return $result;
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
    public function incr($rule_name, $key, $ttl, $increment) {
        $result = self::$_redis->hIncrBy($rule_name, $key, $increment);
        if (self::$_redis->ttl($rule_name) < 0) {
            self::$_redis->expire($rule_name, $ttl);
        }

        return $result;
    }

    /**
     * 先判断后计数
     *
     * @param string $rule_name 规则名
     * @param string $key 唯一标示
     * @param int    $threshold 阈值
     * @param int    $ttl 过期时效
     * @param int    $increment default 1 单次增加频率数量
     *
     * @return int|bool 成功返回已计数次数, 失败返回false
     */
    public function add($rule_name, $key, $threshold, $ttl, $increment = 1) {
        if (!$this->check($rule_name, $key, $threshold)) {
            return false;
        }

        $result = self::$_redis->hIncrBy($rule_name, $key, $increment);
        if (self::$_redis->ttl($rule_name) < 0) {
            self::$_redis->expire($rule_name, $ttl);
        }

        return $result;
    }

    /**
     *
     * @param string $rule_name 规则名
     * @param string $key 唯一标示
     * @param int    $threshold 阈值
     *
     * @return bool|string
     */
    public function checkByNaturalTime($rule_name, $key, $threshold) {
        return $this->check($rule_name, $key, $threshold);
    }

    /**
     *
     * @param string $rule_name 规则名
     * @param string $key 唯一标示
     * @param int    $threshold 阈值
     * @param int    $freqDesc 自然周期 0-每自然日 1-每自然周 2-每自然月 3-每自然年
     * @param int    $increment default 1 单次增加频率数量
     *
     * @return bool|string
     */
    public function addByNaturalTime($rule_name, $key, $threshold, $freqDesc, $increment = 1) {
        return $this->add($rule_name, $key, $threshold, self::getTTL($freqDesc), $increment);
    }

    /**
     * 根据频率描述符获取对应过期时间
     *
     * @param int $freqDesc 1-每自然日 2-每自然周 3-每自然月 4-每自然年
     *
     * @return int
     */
    public static function getTTL($freqDesc) {
        $timestamp = time();
        switch ($freqDesc) {
            case self::TTL_TYPE_DAY :
                return strtotime(date("Ymd000000", strtotime("next Day", $timestamp))) - $timestamp;
            case self::TTL_TYPE_WEEK :
                return strtotime(date("Ymd000000", strtotime("next Week", $timestamp))) - $timestamp;
            case self::TTL_TYPE_MONTH :
                return strtotime(date("Ym01000000", strtotime("next Month", $timestamp))) - $timestamp;
            case self::TTL_TYPE_YEAR :
                return strtotime(date("Y0101000000", strtotime("next Year", $timestamp))) - $timestamp;
            default :
                return 0;
        }
    }

}