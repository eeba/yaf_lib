<?php
namespace Base\Controller;

class AppAbstract extends Controller  {
    protected $route = 'static';

    protected $params;

    public function getParam($key, $default=''){
        //PATH_INFO中的参数
        $path_info_params = $this->getRequest()->getParams();
        $params = array_merge($path_info_params, $_REQUEST);
        return isset($params[$key]) ? $params[$key] : $default;
    }
}