<?php
namespace File;

use Log\Logger;
use Base\Exception;
use OSS\OssClient;
use OSS\Core\OssException;

class Oss extends Abstraction {
    private $config = null;
    private $instance_obj = null;
    private $check_key = ['endpoint', 'key_id', 'key_secret', 'bucket', 'domain'];

    /**
     * @param $name
     *
     * @return OssClient|null
     * @throws Exception
     */
    public function __construct($name){
        if(!$this->instance_obj) {
            $config = \Base\Config::get('server.file.'.$name);

            foreach ($this->check_key as $key) {
                if(!isset($config[$key])){
                    throw new Exception('配置缺少：'.$key, 5100001);
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
     * @param string    $local  源
     * @param string    $target 目标
     * @param int $type 1:上传文件，2:上传内容
     *
     * @return mixed
     */
    public function put($local, $target, $type = 1) {
        try {
            if ($type == 1) {
                $this->instance_obj->uploadFile($this->config['bucket'], $target, $local);
            } else {
                $this->instance_obj->putObject($this->config['bucket'], $target, $local);
            }
            $ret = str_replace('//','/', $this->config['domain'] .'/'. $target);
        } catch (\Exception $e){
            $ret = '';
            Logger::error('上传失败', ['local'=>$local, 'target'=>$local, $e->getMessage()]);
        }

        return $ret;
    }

    /**
     * form表单上传文件，单个文件
     * @param        $file
     * @param int    $type
     *
     * @return mixed|string
     */
    public function upload($file, $type = 1){
        if($file['error']){
            return '';
        }

        $root_path  = isset($this->config['root_path']) && $this->config['root_path']?$this->config['root_path']:APP;
        $local = $file['tmp_name'];
        if($type == 1) {
            $target = $root_path . '/' . date('Ym') . '/' . md5($file['name']) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        }else{
            $target = $root_path . '/' . date('Ym') . '/' . $file['name'];
        }
        $target = strtolower(trim(str_replace('//', '/', $target), '/'));
        return $this->put($local, $target);
    }


    /**
     * 下载
     *
     * @param        $target
     * @param string $local 本地目录，为空时下载到内存
     *
     * @return string
     * @throws Exception
     */
    public function get($target, $local = '') {
        try{
            if($local){
                $options = array(
                    OssClient::OSS_FILE_DOWNLOAD => $local
                );

                $ret = $this->instance_obj->getObject($this->config['bucket'], $target, $options);
            } else {
                $ret = $this->instance_obj->getObject($this->config['bucket'], $target);
            }
        } catch (\Exception $e){
            Logger::warning('下载失败', [$e->getMessage()]);
            throw new Exception($e->getMessage(), 5100004);
        }

        return $local ? ($ret ? false : true) : $ret;
    }
}