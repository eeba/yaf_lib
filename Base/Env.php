<?php
namespace Base;

define('APP_ENVIRON_DEV', 'dev');
define('APP_ENVIRON_TEST', 'test');
define('APP_ENVIRON_REAL_TEST', 'real_test');
define('APP_ENVIRON_PRODUCT', 'product');

use Http\Request;
use Http\Response;

/**
 * Class Env
 *
 * @package     Base
 * @description 环境常量定义
 *
 * 1.环境变量设置问题
 * 2.开发测试生成环境判断问题
 */
class Env {

    public static $cli_class;
    public static $module;
    public static $controller;
    public static $action;

    public static function init(\Yaf\Request_Abstract $request) {
        self::$module = $request->getModuleName() ?: '';
        self::$controller = $request->getControllerName() ?: '';
        self::$action = $request->getActionName() ?: '';
    }

    public static function getCliClass() {
        return self::$cli_class;
    }

    public static function setCliClass($class) {
        self::$cli_class = $class;
        return true;
    }

    /**
     * 获取当前环境名称
     *
     * @return string dev|test|real_test|product
     */
    public static function getEnvName() {
        $environ = \Yaf\Application::app()->environ();

        return $environ;
    }

    /**
     * 判断是否生产环境
     *
     * 包括: 仿真和线上正式集群
     *
     * @return bool true-生产环境 false-开发环境
     */
    public static function isProductEnv() {
        if (self::getEnvName() === APP_ENVIRON_PRODUCT || self::getEnvName() === APP_ENVIRON_REAL_TEST) {
            return true;
        }
        return false;
    }

    /**
     * 判断仿真环境
     *
     * @return bool
     */
    public static function isRealTest() {
        if (self::getEnvName() === APP_ENVIRON_REAL_TEST) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否cli模式
     *
     * cli模式定义
     *
     * @link http://php.net/manual/en/features.commandline.introduction.php
     *
     * @return bool true-cli模式 false-非cli模式
     */
    public static function isCli() {
        return php_sapi_name() === 'cli' ? true : false;
    }

    /**
     * 判断是否phpunit环境
     *
     * 根据APP_TEST/phpunit.xml中APP_ENV的值进行判断
     *
     * @return bool true-phpunit环境 false-非phpunit环境
     */
    public static function isPhpUnit() {
        return $_ENV['APP_ENV'] === 'phpunit' ? true : false;
    }

}