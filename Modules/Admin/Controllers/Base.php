<?php
namespace Modules\Admin\Controllers;

use Base\Controller\AdminAbstract;

class Base extends AdminAbstract {
    public function init(){
        $this->setViewpath(LIB_PATH . '/Modules/Admin/Views');
    }
}