<?php

namespace File;

use Log\Logger;
use Base\Exception;
use OSS\OssClient;
use OSS\Core\OssException;

class Oss extends Abstraction
{
    private ?OssClient $instance_obj = null;
    private array $must_exist_key = ['endpoint', 'key_id', 'key_secret', 'bucket'];

    /**
     * @param string $name
     *
     * @return OssClient|null
     * @throws Exception
     */
    public function __construct(string $name = 'server.file.oss')
    {
        if (!$this->instance_obj) {
            $config = \Base\Config::get($name);
            foreach ($this->must_exist_key as $key) {
                if (!isset($config[$key])) {
                    throw new Exception('配置缺少：' . $key, 5100001);
                }
            }

            $this->config = $config;
            try {
                $this->instance_obj = new OssClient($config['key_id'], $config['key_secret'], $config['endpoint']);
            } catch (OssException $e) {
                throw new Exception($e->getMessage(), 5100002);
            }
        }

        return $this->instance_obj;
    }

    /**
     * @param $file
     * @return string
     */
    public function put($file): string
    {
        $target = '';
        try {
            $target = str_replace($this->config['bucket'], "", $this->getFilePath($file));
            $this->instance_obj->uploadFile($this->config['bucket'], $target, $file['tmp_name']);
        } catch (\Exception $e) {
            Logger::error('上传失败', ['local' => $file['tmp_name'], 'target' => $target, $e->getMessage()]);
        }

        return $target;
    }

    /**
     * 下载
     *
     * @param      $target
     * @param null $options
     * @return string
     * @throws Exception
     * <code>
     * $options = array(
     *      OssClient::OSS_PROCESS => "image/auto-orient,1/resize,p_50/quality,q_50"
     * );
     * </code>
     */
    public function get($target, $options = null): string
    {
        try {
            $result = $this->instance_obj->getObject($this->config['bucket'], $target, $options);
        } catch (\Exception $e) {
            Logger::debug("file get:", [$e->getMessage()]);
            throw new Exception($e->getMessage(), 5100004);
        }

        return $result;
    }
}