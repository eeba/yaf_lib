<?php
namespace Common;

use Http\Response;
use Validate\Handler;

abstract class ApiAbstract extends Controller {
    protected $params;

    public function before() {
    }

    public function auth() {
    }

    abstract public function params();

    abstract public function action();

    public function after() {
    }

    public function indexAction() {
        Response::setFormatter(Response::FORMAT_JSON);

        $this->before();
        $this->auth();
        $param = $this->params();
        $this->params = Handler::check($param);
        $this->action();
        $this->after();
    }
}