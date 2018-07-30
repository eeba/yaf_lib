<?php
namespace Base\Controller;

use Http\Response;

abstract class AppAbstract extends Controller  {
    protected $route = 'static';

    protected $params;

    public function getParam($key, $default=''){
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_HTML);

        //PATH_INFO中的参数
        $this->params = $this->getRequest()->getParams();
    }
}