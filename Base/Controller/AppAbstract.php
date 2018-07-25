<?php
namespace Base\Controller;

use Http\Response;

abstract class AppAbstract extends PlainAbstract  {

    public function params() {
        return [];
    }

    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_HTML);
    }
}