<?php
namespace File;

use Log\Logger;
use Base\Exception;

class Local extends Abstraction {
    private $config = null;
    private $instance_obj = null;
    private $check_key = ['bucket'];


    /**
     * Local constructor.
     * @throws Exception
     */
    public function __construct(){
        if(!$this->instance_obj) {
            $config = \Base\Config::get('server.file.local');

            foreach ($this->check_key as $key) {
                if(!isset($config[$key])){
                    throw new Exception('配置缺少：'.$key, 5100001);
                }
            }

            $this->config = $config;
        }
        return $this;
    }

    /**
     * @param     $file
     * @param     $category
     * @param string $filename
     *
     * @return mixed|string
     * @throws Exception
     */
    public function put($file, $category, $filename = ''){
        if($file['error']){
            return '';
        }

        $dir = $this->config['bucket'] . '/' . $category . '/' . date('Ym') . '/';
        $filename = $filename ? : md5_file($file['tmp_name']) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $target = strtolower(str_replace('//', '/', $dir . $filename));

        $this->checkDir($dir);
        $ret = move_uploaded_file($file['tmp_name'], $target);

        if($ret){
            return str_replace($this->config['bucket'] , '', $target);
        }else{
            throw new Exception("put file '" . $file['name'] . "' is fail");
        }
    }


    /**
     * 下载
     *
     * @param        $path
     * @param        $local
     *
     * @return string
     * @throws Exception
     */
    public function get($path, $local = null) {
        $target = $this->config['bucket'] . '/' .$path;
        $target = str_replace('//', '/', $target);
        if(!is_readable($target)){
            Logger::getInstance()->error(["file '$target' is not found or can't be read"]);
            throw new Exception("file '$target' is not found or can't be read");
        }

        $content = file_get_contents($target);
        return $content;
    }


    /**
     * @param $path
     *
     * @return bool
     * @throws Exception
     */
    private function checkDir($path){
        if(is_dir($path)){
            return true;
        }else{
            $ret = mkdir($path, 0777, true);
            if(!$ret){
                throw new Exception(get_class()." can't create the folder ".$path);
            }
            return true;
        }
    }
}