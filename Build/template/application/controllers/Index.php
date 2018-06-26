<?php

class Controller_Index extends Common\AppAbstract{
    public function params() {
        return [];
    }

    public function action() {
       $this->response['content'] = '成功';
    }
}