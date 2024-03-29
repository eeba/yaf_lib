<?php

namespace Security;

/**
 * Class Mask
 * @package Security
 * @description 关键讯息添加马赛克工具类
 */
class Mask
{
    const DEFAULT_MASK_CHAR = '*';  //默认马赛克字符

    const DEFAULT_NUMERIC_HEAD_REST_LEN = 4;  //默认数字型讯息首端保留长度
    const DEFAULT_NUMERIC_TAIL_REST_LEN = 4;  //默认数字型讯息尾端保留长度
    const DEFAULT_MOBILE_HEAD_REST_LEN = 3;  //默认手机号首端保留长度
    const DEFAULT_MOBILE_TAIL_REST_LEN = 4;  //默认手机号尾端保留长度
    const DEFAULT_CERT_NO_REST_LEN = 4;  //默认证件号首尾保留长度
    const DEFAULT_ACCT_NO_REST_LEN = 4;  //默认银行帐号首尾保留长度

    const MASK_FAMILY_NAME = 0;  //姓氏添加马赛克

    /**
     * 手机号添加马赛克
     *
     * @param string $mobile 手机号
     * @param string $maskChar default * 马赛克字符
     * @param int $headRestLen default 3 手机号首端保留长度，默认为3
     * @param int $tailRestLen default 4 手机号尾端保留长度，默认为4
     *
     * @return string
     */
    public static function maskMobile(string $mobile, string $maskChar = self::DEFAULT_MASK_CHAR, int $headRestLen = self::DEFAULT_MOBILE_HEAD_REST_LEN, int $tailRestLen = self::DEFAULT_MOBILE_TAIL_REST_LEN): string
    {
        return self::maskNumericInfo($mobile, $maskChar, $headRestLen, $tailRestLen);
    }

    /**
     * 证件号添加马赛克
     *
     * @param string $certNo 证件号
     * @param string $maskChar default * 马赛克字符
     * @param int $restLen default 4 证件号首尾保留长度，默认为4
     *
     * @return string
     */
    public static function maskCertNo(string $certNo, string $maskChar = self::DEFAULT_MASK_CHAR, int $restLen = self::DEFAULT_CERT_NO_REST_LEN): string
    {
        return self::maskNumericInfo($certNo, $maskChar, $restLen, $restLen);
    }

    /**
     * 银行账号（卡号|存折号）添加马赛克
     *
     * @param string $acctNo 银行账号
     * @param string $maskChar default * 马赛克字符
     * @param int $restLen default 4 证件号首尾保留长度，默认为4
     *
     * @return string
     */
    public static function maskAcctNo(string $acctNo, string $maskChar = self::DEFAULT_MASK_CHAR, int $restLen = self::DEFAULT_ACCT_NO_REST_LEN): string
    {
        return self::maskNumericInfo($acctNo, $maskChar, $restLen, $restLen);
    }

    /**
     * 数字型讯息添加马赛克
     *
     * 通常长度应至少为8位（手机|账号|证件号码）
     *
     * @param string $info 原始讯息
     * @param string $maskChar default * 马赛克字符
     * @param int $headRestLen default 4 首端保留长度
     * @param int $tailRestLen default 4 尾端保留长度
     *
     * @return string
     */
    public static function maskNumericInfo(string $info, string $maskChar = self::DEFAULT_MASK_CHAR, int $headRestLen = self::DEFAULT_NUMERIC_HEAD_REST_LEN, int $tailRestLen = self::DEFAULT_NUMERIC_TAIL_REST_LEN): string
    {
        return empty($info) ? $info : substr($info, 0, $headRestLen) . str_repeat($maskChar, strlen($info) - $headRestLen - $tailRestLen) . substr($info, -$tailRestLen);
    }

    /**
     * 姓名添加马赛克
     *
     * @param string $name 手机号
     * @param string $maskChar default * 马赛克字符
     * @param int $maskType default 0 马赛克类型 0-姓氏马赛克 1-名字马赛克
     *
     * @return string
     */
    public static function maskName(string $name, string $maskChar = self::DEFAULT_MASK_CHAR, int $maskType = self::MASK_FAMILY_NAME): string
    {
        if (!$name) {
            return $name;
        }
        $encoding = mb_detect_encoding($name);
        $str_len = mb_strlen($name, $encoding);
        if ($str_len < 4) {
            $maskLen = 1;
        } else if ($str_len < 5) {
            $maskLen = 2;
        } else {
            return $name;
        }

        if (self::MASK_FAMILY_NAME === $maskType) {
            return str_repeat($maskChar, $maskLen) . mb_substr($name, $maskLen, null, $encoding);
        }

        return mb_substr($name, 0, $str_len - $maskLen, $encoding) . str_repeat($maskChar, $maskLen);
    }

}