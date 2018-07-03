<?php

class Controller_Index extends Base\Controller\PlainAbstract {
    public function params() {
        return [];
    }

    public function action() {
        var_dump(Yaf\Registry::get('a'));
        echo '成功';
    }
}