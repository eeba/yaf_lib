<?php
namespace Modules\Admin\Controllers;

use S\Http\Request;
use Base\Exception;
use Modules\Admin\Model\Data\Role as DataRole;

/**
 * @funcname 角色管理
 * Class Role
 */
class Role extends Base {

    /**
     * @funcname 角色列表
     */
    public function indexAction() {
        if(\S\Http\Request::isAjax()){
            $list = (new DataRole())->dataTable();
            $this->response = $list;
        }
    }

    /**
     * @funcname 保存角色信息
     *
     * @throws Exception
     */
    public function saveAction() {
        $id = Request::request('id', 0);
        $name = Request::request('name');
        $status = Request::request('status');

        if(!$name){
            throw new Exception('请输入分组名');
        }
        $data_role = new DataRole();
        $info = $data_role->getInfoByName($name);
        if($info){
            throw new Exception("`{$name}`已存在");
        }
        $data_role->save($id, $name, $status);
    }

    /**
     * @funcname 获取角色详情
     */
    public function detailAction(){
        $id = Request::request('id');
        $ret = (new DataRole())->getInfoById($id);
        $this->response = $ret;
    }
}
