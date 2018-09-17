<?php
namespace Base\Controller;

use Base\Env;
use Base\Exception;
use Http\Request;

class AdminAbstract extends AppAbstract {
    protected $route = 'static';

    protected $params;

    protected $all_access_uri = array(
        '/access/login',
        '/access/dologin',
        '/access/qrlogin',
        '/access/qrcheck',
        '/access/allowlogin',
        '/access/refuselogin',
    );

    public function getParam($key, $default=''){
        //PATH_INFO中的参数
        $path_info_params = $this->getRequest()->getParams();
        $params = array_merge($path_info_params, $_REQUEST);
        return isset($params[$key]) ? $params[$key] : $default;
    }

    public function init() {
        parent::init();
        $this->checkAccessLogin();
        $this->checkAuthorization();
    }

    public function checkAccessLogin(){
        $request_uri = $this->getRequest()->getRequestUri();
        if(!in_array(strtolower($request_uri), $this->all_access_uri) && !isset($_SESSION['admin_info']) ){
            if(Request::isAjax()){
                $msg = '你现在是退出状态，请先登录!';
            }else{
                $msg = "你现在是退出状态，请先登录! <a href='/access/login'>登录</a>";
            }
            throw new Exception($msg);
        }
    }

    public function checkAuthorization(){

    }
 }