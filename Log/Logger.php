<?php

namespace Log;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\MercurialProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;

/**
 * @method static debug(string $message, array $context = []) 详细的debug信息
 * @method static info(string $message, array $context = []) 关键事件
 * @method static notice(string $message, array $context = []) 普通但是重要的事件
 * @method static warning(string $message, array $context = []) 出现非错误的异常
 * @method static error(string $message, array $context = []) 运行时错误，但是不需要立刻处理
 * @method static critical(string $message, array $context = []) 严重错误
 * @method static alert(string $message, array $context = []) 整个网站关闭，数据库不可用等
 * @method static emergency(string $message, array $context = []) 系统不可用
 */
class Logger
{
    private static \Monolog\Logger $_instance;

    private function __construct()
    {
    }

    private static function getInstance(): \Monolog\Logger
    {
        if (is_null(self::$_instance)) {
            $logger = new \Monolog\Logger(APP);
            $logger->pushHandler(new StreamHandler(self::getPath(), \Monolog\Logger::DEBUG));
            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new MemoryPeakUsageProcessor());
            $logger->pushProcessor(new MemoryUsageProcessor());
            self::$_instance = $logger;
        }
        return self::$_instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $function_num = strtolower($name);
        if (!in_array(strtoupper($name), \Monolog\Logger::getLevels())) {
            $function_num = 'debug';
        }
        $message = $arguments[0];
        $context = $arguments[1] ?: [];
        self::getInstance()->$function_num($message, $context);
    }

    private static function getPath(): string
    {
        $controller_name = strtolower(\Base\Env::getControllerName());
        $path = strtolower(LOG_PATH . DIRECTORY_SEPARATOR . $controller_name . DIRECTORY_SEPARATOR . date("Ym") . DIRECTORY_SEPARATOR . date("Ymd") . ".log");
        return str_replace('//', '/', $path);
    }
}