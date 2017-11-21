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
        self::$while++;
        $num = '';
        $num .= microtime(true) * 10000;//14位 时间戳
        $num .= substr(crc32(gethostname()), 0, 2);//2位 主机号
        $num .= str_pad(substr(self::$while, -4), 4, 0, STR_PAD_LEFT);//4位 顺序号
        $num .= str_pad(mt_rand(0, 9999), 4, 0, STR_PAD_LEFT);//4位 随机数

        return self::Hex($num, $flag);
    }

    /**
     * 进制转换
     * @param $num
     * @param $flag
     * @return string
     */
    public static function Hex($num, $flag=''){
        $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        $hex = 62;
        do {
            $ret = $dict[bcmod($num, $hex)] . $ret;
            $num = bcdiv($num, $hex, 0);
        } while ($num > 0);

        return $flag . $ret;
    }
}
