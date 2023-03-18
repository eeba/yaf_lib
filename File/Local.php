<?php

namespace File;

use Base\Config;
use Log\Logger;
use Base\Exception;

class Local extends Abstraction
{
    private Local|null $instance_obj = null;
    private array $must_exist_key = ['bucket'];


    /**
     * Local constructor.
     * @throws Exception
     */
    public function __construct(string $name = "server.file.local")
    {
        if (!$this->instance_obj) {
            $config = Config::get($name);
            foreach ($this->must_exist_key as $key) {
                if (!isset($config[$key])) {
                    throw new Exception('配置缺少：' . $key, 5100001);
                }
            }

            $this->config = $config;
        }
        return $this;
    }

    /**
     * @param $file
     * @return string
     * @throws Exception
     */
    public function put($file):string
    {
        if ($file['error']) {
            throw new Exception("put file '" . $file['name'] . "' is fail, error:" . $file['error']);
        }

        $target = $this->getFilePath();
        $this->checkDir($target);
        $ret = move_uploaded_file($file['tmp_name'], $target);

        if ($ret) {
            return str_replace($this->config['bucket'], '', $target);
        } else {
            throw new Exception("put file '" . $file['name'] . "' is fail");
        }
    }


    /**
     * 下载
     *
     * @param $target
     * @param null $options
     * @return string
     * @throws Exception
     */
    public function get($target, $options = null): string
    {
        $target = $this->config['bucket'] . '/' . $target;
        $target = str_replace('//', '/', $target);
        if (!is_readable($target)) {
            Logger::error('目录不存在/不可读', ["file '$target' is not found or can't be read"]);
            throw new Exception("file '$target' is not found or can't be read");
        }

        return file_get_contents($target);
    }


    /**
     * @param $target
     * @return void
     * @throws Exception
     */
    private function checkDir($target): void
    {
        $path = pathinfo($target, PATHINFO_DIRNAME);
        if (!is_dir($path)) {
            $ret = mkdir($path, 0777, true);
            if (!$ret) {
                throw new Exception(get_class() . " can't create the folder " . $path);
            }
        }
    }
}