<?php
namespace Modules\Admin\Controllers;

use S\Http\Request;
use Modules\Admin\Model\Service\Access;

/**
 * @funcname 登录 /退出
 */
class Login extends Common {

    /**
     * @funcname 管理员登录页面
     */
    public function indexAction() {
        if (isset($_SESSION['admin_info']) && isset($_SESSION['admin_acl'])) {
            $this->redirect(APP_ADMIN_HOST.'/index/index');
            exit();
        }
    }

    /**
     * @funcname 执行管理员登录
     * @throws \Base\Exception
     */
    public function doLoginAction() {
        $username = Request::request('username');
        $password = Request::request('password');
        $verify_code = Request::request('verify_code');
        if (!$username) {
            throw new \Base\Exception('账号错误');
        }
        if (!$password) {
            throw new \Base\Exception('密码错误');
        }
        if(strtolower($verify_code) != $_SESSION['verify_code']){
            throw new \Base\Exception('验证码错误');
        }

        (new Access())->login($username, $password);
        unset($_SESSION['verify_code']);
    }

    /**
     * @funcname 管理员退出
     */
    public function logoutAction() {
        (new Access())->logout();
        $this->redirect(APP_ADMIN_HOST . '/login/index');
    }

    /**
     * @funcname 管理员登录验证码
     */
    public function captchaAction(){
        (new \S\Captcha\Image())->show();
    }

}
