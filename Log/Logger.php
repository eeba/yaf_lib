<?php

namespace Log;

class Logger
{
    private static $_instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function debug(array $data = [], $path = "")
    {
        $this->addRecord('debug', $data, $path);
    }

    public function info(array $data = [], $path = "")
    {
        $this->addRecord('info', $data, $path);
    }

    public function notice(array $data = [], $path = "")
    {
        $this->addRecord('notice', $data, $path);
    }

    public function warning(array $data = [], $path = "")
    {
        $this->addRecord('warning', $data, $path);
    }

    public function error(array $data = [], $path = "")
    {
        $this->addRecord('error', $data, $path);
    }

    public function critical(array $data = [], $path = "")
    {
        $this->addRecord('critical', $data, $path);
    }

    public function alert(array $data = [], $path = "")
    {
        $this->addRecord('alert', $data, $path);
    }

    public function emergency(array $data = [], $path = "")
    {
        $this->addRecord('emergency', $data, $path);
    }

    private function addRecord($level, $data, $path)
    {
        $file_path = self::getPath($level, $path);
        $dir_path = dirname($file_path);
        if (!is_dir($dir_path)) {
            @mkdir($dir_path, 0777, true);
            @chmod($dir_path, 0777);
        }

        $ret = true;
        $data = array_merge(
            [
                'request_id'   => $_SERVER['HTTP_REQUEST_ID'],
                'request_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'current_time' => date('Y-m-d H:i:s'),
                'exec_time'    => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
                'client_ip'    => \Util\Ip::getClientIp(),
                'server_ip'    => \Util\Ip::getServerIp(),
            ],
            $data
        );

        $data = array_map(function ($item){
            return is_array($item) ? json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $item;
        }, $data);

        if (false === file_put_contents($file_path, implode(' | ', $data) . "\n", FILE_APPEND | LOCK_EX)) {
            $ret = false;
        }
        return $ret;
    }

    private function getPath($level, $path)
    {
        if (!$path) {
            $cli_class_name = \Base\Env::getControllerName();//todo
            $key = strtolower(str_replace('_', '/', $cli_class_name));
        } else {
            $key = $path;
        }
        $path = strtolower(LOG_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . date("Ym") . DIRECTORY_SEPARATOR . $level . '.' . date("Ymd") . ".log");
        return str_replace('//', '/', $path);
    }
}