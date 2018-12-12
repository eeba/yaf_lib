<?php
namespace Base\Controller;

use Base\Env;
use Base\Exception;
use Http\Request;

class WebAbstract extends Controller  {
    protected $route = 'static';

    protected $params;

    protected $all_access_uri = [];

    public function getParam($key, $default=''){
        //PATH_INFO中的参数
        $path_info_params = $this->getRequest()->getParams();
        $params = array_merge($path_info_params, $_REQUEST);
        return isset($params[$key]) ? $params[$key] : $default;
    }
 }