<?php

namespace Base;

class Config {

    private static $config = [];

    /**
     * 获取配置
     *
     * @param string $name
     * @return string
     */
    public static function get($name = '') {
        $value = '';
        if (!$name) {
            return $value;
        }
        $names = explode('.', $name);
        $file_path = CONF_PATH;
        $file = '';
        while (!file_exists($file) && $names) {
            $file_name = array_shift($names);
            $file_path .= DIRECTORY_SEPARATOR . $file_name;
            $file = $file_path . ".php";
        }

        $config = self::getConfig($file);
        foreach ($names as $value) {
            if (isset($config[$value])) {
                $config = $config[$value];
            } else {
                $config = null;
                break;
            }
        }
        return $config;
    }

    /**
     * 获取配置文件内容
     *
     * @param $path
     * @return mixed
     */
    private static function getConfig($path) {
        $mark = md5($path);
        $config = null;
        if (isset(self::$config[$mark])) {
            return self::$config[$mark];
        } else {
            if (is_file($path)) {
                $config = self::$config[$mark] = include $path;
            }
        }
        return $config;
    }
}