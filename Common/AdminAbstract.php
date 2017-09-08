<?php
namespace Common;

abstract class AdminAbstract extends Controller {
    protected $route = 'simple';

    public function init() {
        parent::init();
        $app_info = $_SESSION['app_info'];
        if (!$app_info['app_key']) {
            $this->redirect('/admin/login/index');
        }
    }

}
