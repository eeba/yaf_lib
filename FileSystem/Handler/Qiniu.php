<?php
namespace FileSystem\Handler;

use Base\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu extends Abstraction {

    private $upload;
    private $token;
    private $config;

    public function __construct($config) {
        $access = Config::get('service.file.' . $config . '.access');
        $secret = Config::get('service.file.' . $config . '.secret');
        $bucket = Config::get('service.file.' . $config . '.bucket');
        $this->config = $config;
        if (!$this->token[$config]) {
            $auth = new Auth($access, $secret);
            $this->token[$config] = $auth->uploadToken($bucket);
        }
        $this->upload = $this->upload ?: new UploadManager();
    }

    /**
     * 文件存储
     * @param string $remote_file_name
     * @param string $local_file_name
     * @return bool
     */
    public function put($remote_file_name, $local_file_name) {
        list($ret, $err) = $this->upload->putFile($this->token[$this->config], $remote_file_name, $local_file_name);
        if ($err !== null) {
            return false;
        } else {
            return $ret;
        }
    }

    public function get($space, $remote_file_name) {
        // TODO: Implement get() method.
    }

    public function delete($space, $remote_file_name) {
        // TODO: Implement delete() method.
    }
}