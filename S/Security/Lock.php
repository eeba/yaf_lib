<?php
namespace S\Security;

use S\Data\Redis;

/**
 * Class Lock
 * @package S
 * @description 锁
 */
class Lock {

    const DEFAULT_EXPIRE = 10;//默认时效 秒
    const DEFAULT_WAIT = 3;//秒
    const DEFAULT_WAIT_SEC = 100;//毫秒
    const DEFAULT_VALUE = 1;

    /**
     * 互斥锁 CAS
     * 基于时间一直没有误差
     * 如果时间较短 有死锁风险
     * @param     $lock_id
     * @param int $expire
     * @return bool
     */
    public static function mutexLock($lock_id, $expire = self::DEFAULT_EXPIRE) {
        $redis = new Redis();
        $ret = $redis->set($lock_id, self::DEFAULT_VALUE, array('nx', 'ex' => $expire));
        return $ret ? true : false;
    }

    /**
     * 阻塞锁
     * @param $lock_id
     * @param $expire
     * @param $wait_time
     * @return bool
     */
    public static function blockLock($lock_id, $expire = self::DEFAULT_EXPIRE, $wait_time = self::DEFAULT_WAIT) {
        $ret = self::mutexLock($lock_id, $expire);
        if ($ret) {
            return true;
        } else {
            $i = 0;
            $num = intval(($wait_time * 1000) / self::DEFAULT_WAIT_SEC);
            while ($i < $num) {
                $ret = self::mutexLock($lock_id, $expire);
                if ($ret) {
                    return true;
                } else {
                    usleep(self::DEFAULT_WAIT_SEC * 1000);
                    $i++;
                }
            }
            return false;
        }
    }

    /**
     * 解除锁
     *
     * @return bool true-解除成功 false-解除失败
     * @throws \Exception
     */
    public static function unlock($lock_id) {
        $redis = new Redis();
        $ret = $redis->del($lock_id);
        return $ret ? true : false;
    }

}