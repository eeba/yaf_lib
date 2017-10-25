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

    public static $_instance;
    public static $_channel;

    public function __construct($channel) {
        self::$_channel = $channel;
    }

    /**
     * @param string $channel
     * @return mixed
     */
    public static function getInstance($channel = 'default') {
        if (!isset(self::$_instance[$channel])) {
            self::$_instance[$channel] = new self($channel);
        }
        return self::$_instance[$channel];
    }

    public function debug(array $data = [], $path = "") {
        $this->addRecord(self::DEBUG, $data, $path);
    }

    public function info(array $data = [], $path = "") {
        $this->addRecord(self::INFO, $data, $path);
    }

    public function notice(array $data = [], $path = "") {
        $this->addRecord(self::NOTICE, $data, $path);
    }

    public function warning(array $data = [], $path = "") {
        $this->addRecord(self::WARNING, $data, $path);
    }

    public function error(array $data = [], $path = "") {
        $this->addRecord(self::ERROR, $data, $path);
    }

    public function critical(array $data = [], $path = "") {
        $this->addRecord(self::CRITICAL, $data, $path);
    }

    public function alert(array $data = [], $path = "") {
        $this->addRecord(self::ALERT, $data, $path);
    }

    public function emergency(array $data = [], $path = "") {
        $this->addRecord(self::EMERGENCY, $data, $path);
    }

    public function addRecord($level, $data, $path) {
        $file_path = $this->getPath($level, $path);
        $dir_path = dirname($file_path);
        if (!is_dir($dir_path)) {
            @mkdir($dir_path, 0777, true);
        }

        $ret = true;
        $data = array_merge(
            [
                'log_time' => Request::getRequestDataTime(),
                'group' => self::$_channel
            ],
            $data
        );
        if (false === file_put_contents($file_path, json_encode($data, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX)) {
            $ret = false;
        }
        return $ret;
    }

    public function getPath($level, $path) {
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
                if (strtolower($module) == 'index') {
                    $key = $module . DIRECTORY_SEPARATOR . $controller_name;
                } else {
                    $key = $module . DIRECTORY_SEPARATOR . $controller_name . DIRECTORY_SEPARATOR . $action;
                }
            }
        } else {
            $key = $path;
        }
        return strtolower(LOG_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . date("Ym") . DIRECTORY_SEPARATOR . $level_name . '.' . date("Ymd") . ".log");
    }
}