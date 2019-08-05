<?php
namespace Modules\Admin\Controllers;

use Base\Controller\AdminAbstract;

/**
 * @funcname Util
 */
class Util extends Common {


    /**
     * @funcname ueditor上传文件接口
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAction() {
        \S\Http\Response::setFormatter( \S\Http\Response::FORMAT_JSON);
        $action = $this->getParam('action');
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

        $this->response['url'] = $result;
    }
}
