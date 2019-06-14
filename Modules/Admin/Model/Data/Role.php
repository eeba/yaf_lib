<?php
namespace Modules\Admin\Model\Data;

use Modules\Admin\Model\Dao\Db\Role as DbAdminRole;

class Role {
    public function getList($where = []){
        $list = (new DbAdminRole())->getList($where);
        foreach ($list as &$item){
            $item['status_name'] = DbAdminRole::STATUS_MAP[$item['status']];
        }
        return $list;
    }

    public function getInfoById($id){
        return (new DbAdminRole())->findById($id);
    }

    public function getInfoByName($name){
        return (new DbAdminRole())->find(['name' => $name]);
    }

    public function dataTable($where = []) {
        $list = (new DbAdminRole())->dataTable($where);
        foreach ($list['data'] as &$item) {
            $item['status_name'] = DbAdminRole::STATUS_MAP[$item['status']];
        }
        return $list;
    }

    public function save($id, $name, $status){
        $db_admin_role = new DbAdminRole();
        $data = array(
            'name' => $name,
            'status' => $status,
        );

        if($id){
            $ret = $db_admin_role->update($data, ['id'=>$id]);
        }else{
            $ret = $db_admin_role->add($data);
        }

        return $ret;
    }
}