<?php
namespace Common;

use Base\Logger;
use Validate\Handler;

abstract class AppAbstract extends Controller {
    protected $params;

    public function before(){}
    public function auth(){}
    abstract public function params();
    abstract public function action();
    public function after(){}

    public function indexAction(){
        //\Http\Response::setFormatter(\Http\Response::FORMAT_JSON);

        $this->before();
        $this->auth();
        $param = $this->params();
        $this->params = Handler::check($param);
        $this->action();
        $this->after();
    }
}