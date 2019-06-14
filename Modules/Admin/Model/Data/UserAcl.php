<?php
namespace Modules\Admin\Model\Data;

use Modules\Admin\Model\Dao\Db\UserAcl as DbAdminUserAcl;

class UserAcl {
    public function getInfoByUid($uid){
        $ret = (new DbAdminUserAcl())->find(['uid'=>$uid]);

        return $ret? json_decode($ret['uri'], true): [];
    }

    public function save($uid, $uri){
        if(is_string($uri)){
            $uri = explode(',', $uri);
        }
        $db_admin_role_acl = new DbAdminUserAcl();
        $data = array(
            'uid' => $uid,
            'uri' => json_encode($uri),
        );

        if($this->getInfoByUid($uid)){
            $ret = $db_admin_role_acl->update($data, ['uid'=>$uid]);
        }else{
            $ret = $db_admin_role_acl->add($data);
        }

        return $ret;
    }
}