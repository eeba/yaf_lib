<?php
namespace Util;

class Rand {
    const DIGIT = '0123456789',
        ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
        ALNUM = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
        UPNUM = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        LONUM = 'abcdefghijklmnopqrstuvwxyz0123456789',
        LOWER = 'abcdefghijklmnopqrstuvwxyz',
        UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        HUMAN = '2345678ABCEFGHKMNPQRSUVWXYZabcdefhkmnpqsuvwxyz';

    public static function digit($length = 4) {
        return self::rand(self::DIGIT, $length);
    }

    public static function alpha($length = 4) {
        return self::rand(self::ALPHA, $length);
    }

    public static function alnum($length = 4) {
        return self::rand(self::ALNUM, $length);
    }

    public static function lower($length = 4) {
        return self::rand(self::LOWER, $length);
    }

    public static function lower_num($length = 4) {
        return self::rand(self::LONUM, $length);
    }

    public static function upper($length = 4) {
        return self::rand(self::UPPER, $length);
    }

    public static function upper_num($length = 4) {
        return self::rand(self::UPNUM, $length);
    }

    public static function human($length = 4) {
        return self::rand(self::HUMAN, $length);
    }

    public static function rand($chars, $length) {
        $size = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, $size)];
        }
        return $str;
    }

    public static function chinese($length = 3) {
        $string = '七万三上下不与专且世业东丝两严中为举乃久义之乎乐九习书乱争二于五亡亦京亲人仁今从仕令以仪众传伦体何作信俱元兄先光全八公六共兴其具典养兼内再农冬凡出分刘则创初别刺前力功劳北十千南及友发受变口古句可史号司同名后吐君吟吴周命和哀四国土圣在地士壮声处备夕多夜大天太夫失头奇女如妇始子字存孙孝孟学宇守宋官宜实宣家容对小少尔尚尼居山岁左师帝干平年并庄序应建异弟强归当录彼心必志忠恭戈戏成戒战所才改效文断斯方族无既日早时明易星映春是最月有朋朝木本机杨某止正此武母氏民水求汉汤泉注火爱父牛玉王琴生男百目相知石礼社祖神秋秦称究穷立童竹笔系羊群老考者而股能臣自至致苏虽蚕行衰西要见角言让记讲论识诗详语说读谷负贤贫贵赵起身辽迁过运近远连邻金长闯闻陈除雪非革音项顺风食首香马高鲁鸡麦齐王中国';

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= mb_substr($string, mt_rand(0, 332), 1, 'utf-8');
        }
        return $str;
    }
}
