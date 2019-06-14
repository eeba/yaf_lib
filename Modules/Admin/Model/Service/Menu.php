<?php
namespace Modules\Admin\Model\Service;

use Base\Exception;
use Modules\Admin\Model\Data\Menu as DataAdminMenu;
use Modules\Admin\Model\Data\MenuController as DataAdminMenuController;

class Menu {

    /**
     * 获取左侧菜单
     *
     * @param array $session 通过$_SESSION筛选菜单
     *
     * @return bool|int|null
     */
    public function getLeftMenu($session = []){
        //从数据库取菜单
        $menu = (new DataAdminMenu())->getList();

        //从数据库取子菜单
        $menu_child = (new DataAdminMenuController())->getList();
        $tmp_menu_child = [];
        foreach ($menu_child as $key => $value) {
            $uri = str_replace(['controller_','action'], '', strtolower('/admin/'.$value['controller'].'/'.$value['action']));
            if($session && !in_array($uri, $_SESSION['admin_acl'])){
                continue;
            }

            $value['uri'] = str_replace('admin/', '', $uri);
            $tmp_menu_child[$value['mid']][] = $value;
        }

        //菜单、子菜单拼装
        foreach ($menu as $key => $value){
            $menu[$key]['child'] = isset($tmp_menu_child[$value['id']]) ? $tmp_menu_child[$value['id']] : [];
        }

        //去除没有子菜单的父级菜单
        if($session) {
            foreach ($menu as $key => $value) {
                if (!$value['child']) {
                    unset($menu[$key]);
                }
            }
        }

        return $menu;
    }

    /**
     * 删除菜单
     *
     * @param $id
     * @return bool|int|null
     * @throws Exception
     */
    public function deleteById($id){
        $child_list = (new DataAdminMenuController())->getList(['mid'=>$id]);
        if($child_list){
            throw new Exception('存在子菜单不能删除');
        }

        return (new DataAdminMenu())->deleteById($id);
    }

    /**
     * 删除子菜单
     *
     * @param $id
     * @return bool|int|null
     */
    public function deleteChildById($id){
        return (new DataAdminMenuController())->deleteById($id);
    }
}