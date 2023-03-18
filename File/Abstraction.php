<?php

namespace File;

abstract class Abstraction
{
    protected array $config;

    abstract public function put($file): string;

    abstract public function get(string $target, array $options): string;

    public function getFilePath($file): string
    {
        $base_path =  $this->config['bucket'] .DIRECTORY_SEPARATOR. date('Ym').DIRECTORY_SEPARATOR.
        $filename = md5_file($file['tmp_name']) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        return strtolower(str_replace('//', '/', $base_path . $filename));
    }
}