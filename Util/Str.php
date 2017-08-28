<?php
namespace Base\Util;

/**
 * 字符串处理
 */
class Str {
    public static function convert($value, $to_encoding, $from_encoding) {
        if (empty($value)) {
            return $value;
        }
        if (is_array($value)) {
            $arr = array();
            foreach ($value as $k => $v) {
                $arr[mb_convert_encoding($k, $to_encoding, $from_encoding)] = self::convert($v, $to_encoding, $from_encoding);
            }
            return $arr;
        } elseif (is_string($value)) {
            return mb_convert_encoding($value, $to_encoding, $from_encoding);
        }
        return $value;
    }

}