<?php
namespace Modules\Admin\Controllers;

use Modules\Admin\Model\Service\Menu;
use Base\Controller\AdminAbstract;

/**
 * @funcname 后台总页
 * Class Index
 */
class Index extends Base {

    /**
     * @funcname 管理后台总页
     */
    public function indexAction() {
        if (isset($_SESSION['admin_info']) && isset($_SESSION['admin_acl'])) {
            $menu_list = (new Menu())->getLeftMenu($_SESSION);
            $this->response['admin_info'] = $_SESSION['admin_info'];
            $this->response['list'] = $menu_list;
        }else{
            $this->redirect(APP_ADMIN_HOST . '/login/index');
        }
    }

    /**
     * @funcname 后台欢迎页面
     */
    public function welcomeAction() {

    }
}
