<?php
namespace Modules\Admin\Controllers;

use Base\Controller\AdminAbstract;
use Base\Exception;
use Modules\Admin\Model\Data\Role as DataRole;

/**
 * @funcname 角色管理
 * Class Role
 */
class Role extends Common {

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
        $id = $this->getParam('id', 0);
        $name = $this->getParam('name');
        $status = $this->getParam('status');

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
        $id = $this->getParam('id');
        $ret = (new DataRole())->getInfoById($id);
        $this->response = $ret;
    }
}
