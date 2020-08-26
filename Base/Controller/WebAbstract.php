<?php
namespace Base\Controller;

use \S\Http\Response;
use \S\Http\Request;
use \S\Validate\Handler;

/**
 * Class ApiAbstract
 *
 * Api接口类的Controller继承
 */
abstract class WebAbstract extends Abstraction {
    protected $params = [];

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