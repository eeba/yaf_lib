<?php
namespace Modules\Admin\Controllers;

use Base\Config;
use S\Http\Request;
use S\Http\Response;

/**
 * @funcname Util
 */
class Util extends Base {


    /**
     * @funcname ueditor上传文件接口
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAction() {
        \S\Http\Response::setFormatter( \S\Http\Response::FORMAT_JSON);
        $action = Request::request('action');
        switch ($action){
            case 'config':
                $data = [
                    'imageUrlPrefix' => '',
                    'imageActionName' => 'uploadimage',
                    'imagePath' => '/',
                    'imageFieldName' => 'file',
                    'imageMaxSize' => 4*1024*1024,
                    'imageAllowFiles' => [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
                ];
                break;
            case 'uploadimage':
                $app_key = \Base\Config::get('server.api.u7c.app_key');
                $data = array(
                    'url' => (new \Api\File())->upload($_FILES['file']['tmp_name'], $app_key, $_FILES['file']['name']),
                    'state' => 'SUCCESS',
                    'title' => $_FILES['file']['name'],
                    'original' => $_FILES['file']['name']
                );
                break;
            default:

        }
        $this->response = $data;
    }

    /**
     * @funcname ajax上传文件
     *
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFileAction(){
        $result = (new \Api\File())->upload($_FILES['img']['tmp_name'], 'novel', $_FILES['img']['name']);

        $this->response['url'] = "/admin/util/downloadFile?index=" . $result;
    }


    /**
     * @funcname 下载文件
     */
    public function downloadFileAction(){
        Response::setFormatter(Response::FORMAT_PLAIN);
        $app_key = Config::get('server.api.u7c.app_key');
        $app_secret = Config::get('server.api.u7c.app_secret');
        $index = Request::request('index');
        $t = time();
        $m = \S\Security\Sign::getSign($app_key, $app_secret, $t, ['index' => $index]);

        $this->redirect("https://api.u7c.cn/file/download?app_key={$app_key}&t={$t}&m={$m}&index={$index}");
    }


    public function wordAction(){
        $app_key = Config::get('server.api.u7c.app_key');
        $app_key = Config::get('server.api.u7c.app_key');
        $app_secret = Config::get('server.api.u7c.app_secret');

    }
}
