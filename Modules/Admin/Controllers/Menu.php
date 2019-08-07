<?php
namespace Modules\Admin\Controllers;

use S\Http\Request;
use Modules\Admin\Model\Data\Menu as DataMenu;
use Modules\Admin\Model\Data\MenuController;
use Modules\Admin\Model\Service\Menu as ServiceMenu;

/**
 * @funcname 菜单管理
 * Class Menu
 */
class Menu extends Common {

    /**
     * @funcname 菜单配置
     */
    public function indexAction() {
        //从代码中获取菜单节点
        $list = (new \Base\Node(APP_PATH . '/modules/Admin/controllers'))->nodeList();

        //从数据库取菜单
        $menu = (new DataMenu())->getList();
        $menu_list = (new ServiceMenu())->getLeftMenu();

        $id = Request::request('id');
        $info = null;
        if($id){
            $info = (new MenuController())->getById($id)?:null;
        }

        $this->response['info'] = $info;
        $this->response['menu'] = $menu;
        $this->response['menu_list'] = $menu_list;
        $this->response['list'] = $list;
        $this->response['list_json'] = json_encode($list);
    }

    /**
     * @funcname 添加新菜单
     */
    public function saveMenuAction() {
        $data['id'] = Request::request('id');
        $data['name'] = Request::request('name');
        $data['order'] = (int)Request::request('order');
        (new DataMenu())->save($data);
    }

    /**
     * @funcname 添加子菜单
     */
    public function saveChildMenuAction(){
        $data['id'] = Request::request('id');
        $data['mid'] = Request::request('mid');
        $data['controller'] = Request::request('controller');
        $data['action'] = Request::request('action');
        $data['name'] = Request::request('name');
        $data['order'] = Request::request('order');
        (new MenuController())->add($data);
    }

    /**
     * @funcname 删除菜单
     *
     * @throws \Base\Exception
     */
    public function delAction(){
        $id = Request::request('id');
        (new ServiceMenu())->deleteById($id);
    }

    /**
     * @funcname 删除子菜单
     */
    public function delChildAction() {
        $id = Request::request('id');
        (new ServiceMenu())->deleteChildById($id);
    }

}
