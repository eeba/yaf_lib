<?php
namespace Common;

abstract class AdminAbstract extends Controller {
    protected $route = 'simple';

    public function init() {
        parent::init();
        $app_info = $_SESSION['admin_info'];
        if (!$app_info) {
            $this->redirect('/admin/login/index');
        }
    }

}
