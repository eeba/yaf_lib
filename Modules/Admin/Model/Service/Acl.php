<?php
namespace Modules\Admin\Model\Service;

use Modules\Admin\Model\Data\User;
use Modules\Admin\Model\Data\UserAcl;
use Modules\Admin\Model\Data\RoleAcl;

class Acl {

    public function getByUid($uid){
        $user_acl = (new UserAcl())->getInfoByUid($uid);
        $user_info = (new User())->getInfoById($uid);
        $role_acl = (new RoleAcl())->getInfoByRid($user_info['rid']);
        $all_acl = array_unique(array_merge($role_acl, $user_acl));
        return $all_acl;
    }

    public function getByRid($rid){
        return (new RoleAcl())->getInfoByRid($rid);
    }
}