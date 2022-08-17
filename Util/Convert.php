<?php

namespace Util;

class Convert
{

    /**
     * RGB颜色十六进制转十进制
     * @param $hex
     * @return array
     */
    public static function colorHexDec($hex): array
    {
        if (!preg_match('/^[0-9a-fA-F]{3}$|^[0-9a-fA-F]{6}$/', $hex)) {
            return ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];
        }

        $hex_len = strlen($hex);
        list($r, $g, $b) = array_map(function ($color) {
            return hexdec(str_pad($color, 2, $color));
        }, str_split($hex, $hex_len / 3));

        return ['r' => $r, 'g' => $g, 'b' => $b, 'a' => 0];
    }
}