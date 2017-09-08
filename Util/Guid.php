<?php
namespace Util;

/**
 * Class Guid
 * @package Base\Util
 * @desc 生成全局唯一id
 * 一般对于唯一ID生成的要求主要这么几点：
 * 毫秒级的快速响应
 * 可用性强
 * prefix有连续性方便DB顺序存储
 * 体积小，8字节为佳
 */
class Guid {
    protected static $while = 0;


    /**
     * 通用唯一id $flag+长度11位字符串
     *
     * * * 表现出连续性
     * * 分布式分发不重合
     * 微秒级的快速响应
     *
     * 使用场景
     * 1.短链id
     * 2.订单id
     * 3.追踪id
     * @param string $flag
     * @return string
     */
    public static function getUid($flag = "") {
        $time = microtime(true) * 10000;//14位
        $server_id = substr(crc32($_SERVER['SERVER_ADDR']), 0, 2);//2位
        self::$while++;
        $while = str_pad(substr(self::$while, -2), 2, 0, STR_PAD_LEFT);//2位
        $rand = str_pad(mt_rand(0, 9999), 4, 0, STR_PAD_LEFT);//4位

        $num = $time . $server_id . $while . $rand;
        $to = 62;
        $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        do {
            $ret = $dict[bcmod($num, $to)] . $ret;
            $num = bcdiv($num, $to);
        } while ($num > 0);
        return $flag . $ret;
    }
}
