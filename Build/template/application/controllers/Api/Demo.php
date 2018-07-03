<?php

use Http\Request;
use Http\Response;

/**
 * @name 用户信息
 */
class Controller_Api_Demo extends Controller_Api_Abstract {

    public function params() {
        return array(

        );
    }

    public function action() {
        $this->response['data'] = '成功';
    }
}