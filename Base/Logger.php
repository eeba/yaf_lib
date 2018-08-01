<?php
namespace Base;

use Http\Request;

class Logger {
    const DEBUG = 1;
    const INFO = 2;
    const NOTICE = 3;
    const WARNING = 4;
    const ERROR = 5;
    const CRITICAL = 6;
    const ALERT = 7;
    const EMERGENCY = 8;

    public static $log_name_map = [
        self::DEBUG => 'debug',
        self::INFO => 'info',
        self::NOTICE => 'notice',
        self::WARNING => 'warning',
        self::ERROR => 'error',
        self::CRITICAL => 'critical',
        self::ALERT => 'alert',
        self::EMERGENCY => 'emergency',
    ];

    public static function debug(array $data = [], $path = "") {
        self::addRecord(self::DEBUG, $data, $path);
    }

    public static function info(array $data = [], $path = "") {
        self::addRecord(self::INFO, $data, $path);
    }

    public static function notice(array $data = [], $path = "") {
        self::addRecord(self::NOTICE, $data, $path);
    }

    public static function warning(array $data = [], $path = "") {
        self::addRecord(self::WARNING, $data, $path);
    }

    public static function error(array $data = [], $path = "") {
        self::addRecord(self::ERROR, $data, $path);
    }

    public static function critical(array $data = [], $path = "") {
        self::addRecord(self::CRITICAL, $data, $path);
    }

    public static function alert(array $data = [], $path = "") {
        self::addRecord(self::ALERT, $data, $path);
    }

    public static function emergency(array $data = [], $path = "") {
        self::addRecord(self::EMERGENCY, $data, $path);
    }

    public static function addRecord($level, $data, $path) {
        $file_path = self::getPath($level, $path);
        $dir_path = dirname($file_path);
        if (!is_dir($dir_path)) {
            @mkdir($dir_path, 0777, true);
        }

        $ret = true;
        $data = array_merge(
            [
                'log_time' => Request::getRequestDataTime(),
            ],
            $data
        );
        if (false === file_put_contents($file_path, json_encode($data, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX)) {
            $ret = false;
        }
        return $ret;
    }

    public static function getPath($level, $path) {
        $level_name = self::$log_name_map[$level];
        if (!$path) {
            if (Env::isCli()) {
                $cli_class_name = Env::getCliClass();
                $key = strtolower(str_replace('\\', '/', $cli_class_name));
            } else {
                $module = Env::$module;
                $controller = Env::$controller;
                $action = Env::$action;
                $controller_name = strtolower(str_replace('_', DIRECTORY_SEPARATOR, $controller));
                $key = $module . DIRECTORY_SEPARATOR . $controller_name . DIRECTORY_SEPARATOR . $action;
            }
        } else {
            $key = $path;
        }
        return strtolower(LOG_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . date("Ym") . DIRECTORY_SEPARATOR . $level_name . '.' . date("Ymd") . ".log");
    }
}