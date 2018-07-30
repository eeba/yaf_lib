<?php
namespace Base\Controller;

use Http\Response;

abstract class AppAbstract extends Controller  {
    protected $route = 'static';

    protected $params;

    public function params() {
        return [];
    }

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_HTML);

        //PATH_INFO中的参数
        $this->params = $this->getRequest()->getParams();
    }
}