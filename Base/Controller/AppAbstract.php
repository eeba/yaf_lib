<?php
namespace Base\Controller;

use Http\Response;

abstract class AppAbstract extends Controller  {
    protected $route = 'static';

    public function params() {
        return [];
    }

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_HTML);
    }

    public function get($key, $default=''){

    }
}