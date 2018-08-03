<?php
namespace Base\Controller;

use Validate\Handler;
use Http\Response;

abstract class ApiAbstract extends Controller {
    protected $route = 'map';

    protected $params;

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_JSON);
    }

    public function getParam($key, $default=''){
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }

    public function before() {}

    public function auth() {}

    abstract public function params();

    abstract public function action();

    public function after() {}

    public function indexAction() {
        $this->before();
        $this->auth();
        //验证传入接口的参数
        $this->params = Handler::check($this->params());
        $this->action();
        $this->after();
    }
}