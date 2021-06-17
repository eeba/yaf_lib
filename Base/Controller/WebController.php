<?php

namespace Base\Controller;

use Validate\Handler;

abstract class WebController extends Abstraction
{
    protected array $params = [];

    public function before()
    {
    }

    public function auth()
    {
    }

    /**
     * @return array
     */
    public function params()
    {
        return [];
    }

    abstract public function action();

    public function after()
    {
    }


    public function indexAction()
    {
        $this->before();
        $this->auth();
        $this->params = Handler::check($this->params());
        $this->action();
        $this->after();
        $this->flush();
    }

}