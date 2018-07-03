<?php
namespace Base\Controller;

use Validate\Handler;
use Http\Response;

abstract class PlainAbstract extends Controller {
    protected $params;

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_PLAIN);
    }


    public function before() {}

    public function auth() {}

    abstract public function params();

    abstract public function action();

    public function after() {}

    public function indexAction() {
        $this->before();
        $this->auth();
        $param = $this->params();
        $this->params = Handler::check($param);
        $this->action();
        $this->after();
    }
}