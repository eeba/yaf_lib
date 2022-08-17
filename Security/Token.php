<?php

namespace Security;

use Cache\Redis as Cache;

/**
 * 生成表单校验用的token
 * 防止CSRF攻击
 */
class Token
{
    const RANDOM = "8b6e0b17a6ba914482dfd942fc389cc5";
    const REDIS_KEY = "SECURITY_TOKEN_";
    const REDIS_VALUE = 1;
    const REDIS_TTL = 86400;//3600*24

    /**
     * 生成表单token
     *
     * @param array $data 表单上的既定项
     * @param bool $uniq token是否具有唯一性
     * @return string
     */
    public static function getFormToken(array $data = array(), bool $uniq = false): string
    {
        $controller = (new \Yaf\Request\Http())->getControllerName();
        $time = microtime(true);
        return $time . "|" . $controller . "|" . $uniq . "|" . md5($time . $controller . $uniq . serialize($data) . self::RANDOM);
    }

    /**
     * 校验token
     *
     * @param        $token
     * @param array $data 表单上的既定项
     * @param bool $ttl token有效时间 单位秒
     * @param string|null $controller default null token来源控制器, 默认不校验
     * @return bool
     */
    public static function checkFormToken($token, array $data = array(), bool $ttl = false, string $controller = null): bool
    {
        $ret = explode("|", $token);
        $time = $ret[0];
        $name = $ret[1];
        $uniq = $ret[2];
        $md5_token = $ret[3];
        if ($md5_token !== md5($time . $name . $uniq . serialize($data) . self::RANDOM)) {
            return false;
        }
        if ($ttl && microtime(true) - intval($time) > $ttl) {
            return false;
        }
        if (!empty($controller) && ($name != $controller)) {
            return false;
        }
        if ($uniq) {
            try {
                if (Cache::get(self::REDIS_KEY . $md5_token) == self::REDIS_VALUE) {
                    return false;
                } else {
                    Cache::set(self::REDIS_KEY . $md5_token, self::REDIS_VALUE, self::REDIS_TTL);
                }
            } catch (\Exception $e) {
            }
        }
        return true;
    }
}
