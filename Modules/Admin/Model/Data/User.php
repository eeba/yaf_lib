<?php
namespace Modules\Admin\Model\Data;

use Base\Exception;
use Dao\Db\Admin\User as DbAdminUser;
use Util\Guid;
use Util\Ip;

class User {

    public function getInfoById($id){
        $info = (new DbAdminUser())->find(['id' => $id]);
        return $info;
    }

    public function getInfoByEmail($email){
        $info = (new DbAdminUser())->find(['email' => $email]);
        return $info;
    }

    public function getList($where = []){
        $list = (new DbAdminUser())->getList($where);
        foreach ($list as &$item){
            $item['status_name'] = DbAdminUser::STATUS_MAP[$item['status']];
        }
        return $list;
    }

    public function getInfoByWechatOpenId($wechat_open_id){
        $info = (new DbAdminUser())->find(['wechat_open_id' => $wechat_open_id]);
        return $info;
    }

    public function save($data){
        $id = $data['id'];
        unset($data['id']);
        $db_admin_user = new DbAdminUser();

        $user_info = $this->getInfoById($id);

        if($user_info) {
            unset($data['password']);
            $ret = $db_admin_user->update($data, ['id' => $id]);
        } else {
            if($this->getInfoByEmail($data['email'])){
                throw new Exception('账号已经存在');
            }
            $data['salt'] = substr(Guid::getUid(), -4);
            $data['password'] = md5($data['password'] . $data['salt']);
            $data['register_ip'] = Ip::getClientIp();
            $ret = $db_admin_user->add($data);
        }
        return $ret;
    }

    public function resetPassword($uid, $password){
        $data['salt'] = substr(Guid::getUid(), -4);
        $data['password'] = md5($password . $data['salt']);

        return (new DbAdminUser())->update($data, ['id' => $uid]);
    }
}