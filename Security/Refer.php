<?php

namespace Security;

/**
 * 校验refer方法
 */
class Refer
{
    /**
     * 校验域名
     * @param      $domain
     * @param bool $is_https
     * @return bool
     */
    public static function checkDomain($domain, bool $is_https = false): bool
    {
        $refer = $_SERVER['HTTP_REFERER'] ?? false;
        if (!$refer) {
            return false;
        }
        $ret_refer = parse_url($refer);
        if ($is_https && $ret_refer['scheme'] !== "https") {
            return false;
        }
        if ($ret_refer['host'] !== $domain) {
            return false;
        }
        return true;
    }

    /**
     * 校验域名及URI
     * @param      $uri
     * @param bool $is_https
     * @return bool
     */
    public static function checkUri($uri, bool $is_https = false): bool
    {
        $refer = $_SERVER['HTTP_REFERER'] ?? false;
        if (!$refer) {
            return false;
        }
        $ret_uri = parse_url($uri);
        $ret_refer = parse_url($refer);
        if ($ret_uri['host'] !== $ret_refer['host']) {
            return false;
        }
        if ($ret_uri['path'] !== $ret_refer['path']) {
            return false;
        }
        if ($is_https && $ret_refer['scheme'] !== "https") {
            return false;
        }
        return true;
    }
}