<?php

namespace Modules\Admin\Model\Service;

use Base\Exception;
use Modules\Admin\Model\Data\User as DataAdminUser;
use Modules\Admin\Model\Dao\Db\User as DbUser;

class Access {

    /**
     * @param        $username
     * @param        $password
     *
     * @return bool
     * @throws Exception
     */
    public function login($username, $password){
        $info = (new DataAdminUser())->getInfoByEmail($username);
        if(!$info){
            throw new Exception('账号`'.$username.'`不存在');
        }
        if(md5($password.$info['salt']) != $info['password']){
            throw new Exception('密码错误');
        }
        if($info['status'] == DbUser::STATUS_CLOSE){
            throw new Exception('此账号已被禁止登录');
        }
        $_SESSION['admin_info'] = $info;
        $_SESSION['admin_acl'] = (new Acl())->getByUid($info['id']);
        return true;
    }

    /**
     * 退出
     */
    public function logout(){
        session_destroy();
    }
}