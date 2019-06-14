<?php
namespace Modules\Admin\Model\Data;

use Modules\Admin\Model\Dao\Db\RoleAcl as DbAdminRoleAcl;

class RoleAcl {
    public function getInfoByRid($rid){
        $ret = (new DbAdminRoleAcl())->find(['rid'=>$rid]);

        return $ret? json_decode($ret['uri'], true): [];
    }

    public function save($rid, $uri){
        if(is_string($uri)){
            $uri = explode(',', $uri);
        }
        $db_admin_role_acl = new DbAdminRoleAcl();
        $data = array(
            'rid' => $rid,
            'uri' => json_encode($uri),
        );

        if($this->getInfoByRid($rid)){
            $ret = $db_admin_role_acl->update($data, ['rid'=>$rid]);
        }else{
            $ret = $db_admin_role_acl->add($data);
        }

        return $ret;
    }
}