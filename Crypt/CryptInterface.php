<?php

namespace Crypt;

interface CryptInterface
{
    /**
     * 统一加密
     * @param $plain
     * @param $key
     *
     * @return mixed
     */
    public static function encrypt($plain, $key): mixed;

    /**
     * 统一解密
     * @param $enplain
     * @param $key
     *
     * @return mixed
     */
    public static function decrypt($enplain, $key): mixed;
}