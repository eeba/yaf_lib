<?php
namespace Base\Controller;

use Http\Response;

abstract class ApiAbstract extends PlainAbstract  {
    public function init() {
        parent::init();
        Response::setFormatter(Response::FORMAT_JSON);
    }
}