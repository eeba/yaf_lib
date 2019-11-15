<?php
namespace Modules\Admin\Controllers;

use S\Http\Request;
use Base\Exception;
use Modules\Admin\Model\Data\User as DataUser;
use Modules\Admin\Model\Data\Role;
use Modules\Admin\Model\Service\Access;

/**
 * @funcname 管理员管理
 */
class User extends Base {

    /**
     * @funcname 管理员列表
     */
    public function indexAction() {
        $user_list = (new DataUser())->getList();
        $role_list = (new Role())->getList(['status'=>\Modules\Admin\Model\Dao\Db\Role::STATUS_OPEN]);
        $rid_name = array_combine(array_column($role_list, 'id'), array_column($role_list, 'name'));

        $this->response['user_list'] = $user_list;
        $this->response['role_list'] = $role_list;
        $this->response['rid_name'] = $rid_name;
    }

    /**
     * @funcname 保存管理员信息
     *
     * @throws Exception
     */
    public function saveAction() {
        $id = Request::request('id');
        $data['email'] = Request::request('email');
        $data['password'] = Request::request('password');
        $data['nickname'] = Request::request('nickname');
        $data['phone'] = Request::request('phone');
        $data['rid'] = Request::request('role');
        $data['status'] = Request::request('status');

        if(($id && count(array_filter($data)) != 5) || (!$id && count(array_filter($data)) != 6)){
            throw new Exception('缺少参数');
        }

        $data['id'] = $id;
        (new DataUser())->save($data);
    }

    /**
     * @funcname 获取管理员信息
     */
    public function detailAction() {
        $id = Request::request('id');
        $user_info = (new DataUser())->getInfoById($id);

        $this->response['user_info'] = $user_info;
    }

    /**
     * @funcname 重置密码
     *
     * @throws Exception
     */
    public function resetPasswordAction(){
        $user_info = $_SESSION['admin_info'];
        if(\S\Http\Request::isAjax()){
            $ori_password = Request::request('ori_password');
            $new_password = Request::request('new_password');
            $re_new_password = Request::request('re_new_password');
            if(!$ori_password){
                throw new Exception('请输入原密码');
            }
            if(!$new_password){
                throw new Exception('请输入新密码');
            }
            if(md5($ori_password . $user_info['salt']) != $user_info['password']){
                throw new Exception('原密码输入错误');
            }
            if($new_password != $re_new_password){
                throw new Exception('`新密码`与`重新输入密码`不一致');
            }

            (new DataUser())->resetPassword($user_info['id'], $new_password);
            (new Access())->logout();

        } else {
            $this->response['user_info'] = $user_info;
        }
    }

}