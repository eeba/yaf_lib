<?php
namespace FileSystem\Handler;

use Base\Exception;
use Base\Config;


class Local extends Abstraction {

    protected $config_key = 'local';
    private $path = null; //文件存储的目录

    public function __construct(){
        $conf = Config::get('service.file.'. $this->config_key);
        $this->path = $conf['bucket'];

        if(!is_dir($this->path)){
            $ret = mkdir($this->path, 0777, true);
            if(!$ret){
                throw new Exception(get_class()." can't create the folder ".$this->path);
            }
        }
    }

    /**
     *  上传文件
     *
     * @param string $space 文件的存储目录
     * @param string $local_file_name  需要上传的文件的路径 绝对路径
     * @param string $remote_file_name 存储在文件系统里的文件名 如有目录则采用'test/1.png'的格式
     * @return mixed  文件的绝对地址
     * @throws Exception
     */
    public function put($local_file_name, $remote_file_name){

        if(!is_file($local_file_name)){
            throw new Exception("file $local_file_name is nou found");
        }

        $store_path = $this->path . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR;

        if (!is_dir($store_path)) {
            $ret = mkdir($store_path, 0777, true);
            if(!$ret){
                throw new Exception(get_class()." can't create the folder ".$store_path);
            }
        }

        $ret = move_uploaded_file($local_file_name, $store_path . $remote_file_name);

        if($ret){
            return $store_path . $remote_file_name;
        }else{
            throw new Exception("put file '$local_file_name' is fail");
        }
    }

    /**
     * 获取文件
     *
     * @param string $space 文件存储的目录
     * @param string $remote_file_name 存储在文件系统里的文件名 如有目录则采用'test/1.png'的格式
     * @return string 文件内容
     * @throws Exception
     */
    public function get($space, $remote_file_name){
        $this->checkSpaceName($space);
        $this->checkSpace($space);
        if(strpos($remote_file_name, $this->dir) !== false){
            $remote_file_name = str_replace($this->dir.'/'.$space.'/', '', $remote_file_name);
        }
        $store_path = $this->dir.'/'.$space.'/'.$remote_file_name;
        if(!is_readable($store_path)){
            throw new Exception("file '$remote_file_name' is not found or can't be read");
        }

        $ret = file_get_contents($store_path);
        return $ret;
    }

    /**
     * 删除文件
     *
     * @param string $space 文件存储的目录
     * @param string $remote_file_name 存储在文件系统里的文件名 如有目录则采用'test/1.png'的格式
     * @return bool
     * @throws Exception
     */
    public function delete($space, $remote_file_name){
        $this->checkSpaceName($space);
        $this->checkSpace($space);
        if(strpos($remote_file_name, $this->dir) !== false){
            $remote_file_name = str_replace($this->dir.'/'.$space.'/', '', $remote_file_name);
        }
        $store_path = $this->dir.'/'.$space.'/'.$remote_file_name;
        if(!is_file($store_path)){
            return true;
        }

        $ret = unlink($store_path);
        if($ret){
            return true;
        }else{
            throw new Exception("delete file '$remote_file_name' fail");
        }
    }

    private function checkSpace($space){
        $path = $this->dir.'/'.$space;
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