<?php
namespace Base\Controller;

use S\Http\Response;
use S\Validate\Handler;

/**
 * Class ApiAbstract
 *
 * 控制台类的Controller继承
 */
abstract class ApiAbstract extends ControllerAbstract {
    protected $params = [];

    public function init() {
        Response::setFormatter(Response::FORMAT_JSON);
    }

    public function before() {}

    public function auth() {}

    public function params(){
        return [];
    }

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