<?php

namespace Base;

class Config
{
    public static function get($name = '')
    {
        $value = '';
        if (!$name) {
            return $value;
        }
        $names = explode('.', $name);
        $file_path = CONF_PATH;
        $file = '';
        while (!file_exists($file) && !is_file($file) && $names) {
            $file_name = array_shift($names);
            $file_path .= DIRECTORY_SEPARATOR . $file_name;
            $file = $file_path . ".ini";
        }

        $config = (new \Yaf_Config_Ini($file))->toArray();
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
}