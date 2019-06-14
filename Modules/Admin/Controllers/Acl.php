<?php
namespace Modules\Admin\Controllers;

use S\Http\Request;
use Base\Controller\AdminAbstract;
use Base\Exception;
use Modules\Admin\Model\Data\RoleAcl;
use Modules\Admin\Model\Data\UserAcl;
use Modules\Admin\Model\Service\Acl as ServiceAcl;

/**
 * @funcname 权限管理
 * Class Acl
 */
class Acl extends Common {

    /**
     * @funcname 权限列表
     */
    public function indexAction() {
        $role_id = Request::get('role_id', 0);
        $user_id = Request::get('user_id', 0);

        $action_list = (new \Base\Node(APP_PATH . '/modules/Admin/controllers'))->nodeList();
        $data = [];
        foreach ($action_list as $action){
            if(!isset($action['method'])){
                continue;
            }
            foreach ($action['method'] as $method){
                $data[$action['controller']]['controller'] = array(
                    'controller' => $action['controller'],
                    'controller_name' => $action['controller_name'],
                    'method_num' => count($action['method'])+1,
                );
                $data[$action['controller']]['method'][] = array(
                    'controller' => $action['controller'],
                    'controller_name' => $action['controller_name'],
                    'action' => $method['action'],
                    'action_name' => $method['action_name'],
                    'uri' => '/admin/' . str_replace(['controller_','action'],'', strtolower($action['controller'] . '/' . $method['action'])),
                );
            }
        }

        if($role_id){
            $all_acl = (new ServiceAcl())->getByRid($role_id);
        } else {
            $all_acl = (new ServiceAcl())->getByUid($user_id);
        }

        $this->response['role_id'] = $role_id;
        $this->response['user_id'] = $user_id;
        $this->response['action_list'] = $data;
        $this->response['all_acl'] = $all_acl;
    }

    /**
     * @funcname 更新权限
     *
     * @throws Exception
     */
    public function saveAction() {
        $user_id = $this->getParam('user_id');
        $role_id = $this->getParam('role_id');
        $uri = $this->getParam('uri');
        if($user_id){
            (new UserAcl())->save($user_id, $uri);
        } elseif ($role_id){
            (new RoleAcl())->save($role_id, $uri);
        } else {
            throw new Exception('缺少参数');
        }
        $this->response['data'] = $uri;
    }
}
