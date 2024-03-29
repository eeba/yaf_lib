<?php

namespace Util;

use ipip\db\City;
use JetBrains\PhpStorm\ArrayShape;

class Ip
{

    /**
     * 服务器IP地址
     * @return string
     */
    public static function getServerIp(): string
    {
        if (isset($_SERVER['SERVER_ADDR'])) {
            return $_SERVER['SERVER_ADDR'];
        }
        return '';
    }

    /**
     * 客户端IP地址
     * @return string
     */
    public static function getClientIp(): string
    {
        if (PHP_SAPI == 'cli') {
            return self::getServerIp();
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        return $ip;
    }

    /**
     * 获取客户端IP地理位置信息
     *
     * @param string $ip
     *
     * @return array
     */
    public static function getInfo(string $ip = ''): array
    {
        $city = new City(__DIR__ . '/ipipfree.ipdb');
        $ip = $ip ?: self::getClientIp();
        $data = $city->findMap($ip, 'CN');
        return array(
            'country' => $data['country_name'] ?: '',
            'region' => $data['region_name'] ?: '',
            'city' => $data['city_name'] ?: '',
        );
    }
}