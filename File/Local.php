<?php

namespace File;

use Log\Logger;
use Base\Exception;

class Local extends Abstraction
{
    private $config = null;
    private $instance_obj = null;
    private $check_key = ['bucket'];


    /**
     * Local constructor.
     * @throws Exception
     */
    public function __construct()
    {
        if (!$this->instance_obj) {
            $config = \Base\Config::get('server.file.local');

            foreach ($this->check_key as $key) {
                if (!isset($config[$key])) {
                    throw new Exception('配置缺少：' . $key, 5100001);
                }
            }

            $this->config = $config;
        }
        return $this;
    }

    /**
     * @param     $local
     * @param     $category
     * @param string $filename
     *
     * @return array|string|string[]
     * @throws Exception
     */
    public function put($local, $category, $filename = '')
    {
        if ($local['error']) {
            return '';
        }

        $dir = $this->config['bucket'] . '/' . $category . '/' . date('Ym') . '/';
        $filename = $filename ?: md5_file($local['tmp_name']) . '.' . pathinfo($local['name'], PATHINFO_EXTENSION);
        $target = strtolower(str_replace('//', '/', $dir . $filename));

        $this->checkDir($dir);
        $ret = move_uploaded_file($local['tmp_name'], $target);

        if ($ret) {
            return str_replace($this->config['bucket'], '', $target);
        } else {
            throw new Exception("put file '" . $local['name'] . "' is fail");
        }
    }


    /**
     * 下载
     *
     * @param        $target
     * @param        $local
     *
     * @return string
     * @throws Exception
     */
    public function get($target, $local = null): string
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
     * @param $path
     *
     * @return bool
     * @throws Exception
     */
    private function checkDir($path): bool
    {
        if (!is_dir($path)) {
            $ret = mkdir($path, 0777, true);
            if (!$ret) {
                throw new Exception(get_class() . " can't create the folder " . $path);
            }
        }
        return true;
    }
}