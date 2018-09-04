<?php
namespace Base;

use Http\Request;

class Logger {
    private static $_instance;

    private function __construct(){}

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function debug(array $data = [], $path = "") {
        $this->addRecord('debug', $data, $path);
    }

    public function info(array $data = [], $path = "") {
        $this->addRecord('info', $data, $path);
    }

    public function notice(array $data = [], $path = "") {
        $this->addRecord('notice', $data, $path);
    }

    public function warning(array $data = [], $path = "") {
        $this->addRecord('warning', $data, $path);
    }

    public function error(array $data = [], $path = "") {
        $this->addRecord('error', $data, $path);
    }

    public function critical(array $data = [], $path = "") {
        $this->addRecord('critical', $data, $path);
    }

    public function alert(array $data = [], $path = "") {
        $this->addRecord('alert', $data, $path);
    }

    public function emergency(array $data = [], $path = "") {
        $this->addRecord('emergency', $data, $path);
    }

    private function addRecord($level, $data, $path) {
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

    private function getPath($level, $path) {
        if (!$path) {
            if (Env::isCli()) {
                $cli_class_name = Env::getCliClass();
                $key = strtolower(str_replace('\\', '/', $cli_class_name));
            } else {
                $key = \Yaf\Dispatcher::getInstance()->getRequest()->getRequestUri();
            }
        } else {
            $key = $path;
        }
        return strtolower(LOG_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . date("Ym") . DIRECTORY_SEPARATOR . $level . '.' . date("Ymd") . ".log");
    }
}