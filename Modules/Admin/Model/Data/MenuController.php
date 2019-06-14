<?php
namespace Modules\Admin\Model\Data;

use Modules\Admin\Model\Dao\Db\MenuController as DaoAdminMenuController;

class MenuController {

    public function add($data){
        $db = new DaoAdminMenuController();
        $info = null;
        if($data['id']){
            $info = $db->find(['id' => $data['id']]);
        }
        if($info){
            return $db->update($data, ['id' => $data['id']]);
        }else {
            return $db->add($data);
        }
    }

    public function getList($where = []){
        return (new DaoAdminMenuController())->getList($where, '*', ['order'=>'asc']);
    }

    public function getById($id){
        return (new DaoAdminMenuController())->find(['id' => $id]);
    }

    public function deleteById($id){
        return (new DaoAdminMenuController())->delete(['id' => $id]);
    }
}