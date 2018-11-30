<?php
namespace Base\Controller;

use Base\Env;
use Base\Exception;
use Http\Request;

class AdminAbstract extends Controller  {
    protected $route = 'static';

    protected $params;

    protected $all_access_uri = [];

    public function getParam($key, $default=''){
        //PATH_INFO中的参数
        $path_info_params = $this->getRequest()->getParams();
        $params = array_merge($path_info_params, $_REQUEST);
        return isset($params[$key]) ? $params[$key] : $default;
    }

    /**
     * @throws Exception
     */
    public function init() {
        $this->checkAccessLogin();
        $this->checkAuthorization();
    }

    /**
     * 检查是否已经登录
     * @throws Exception
     */
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

    /**
     * 检查是否有权限
     */
    public function checkAuthorization(){

    }
 }