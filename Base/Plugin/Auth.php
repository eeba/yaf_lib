<?php

namespace Base\Plugin;


use Base\Exception;

class Auth extends \Yaf_Plugin_Abstract
{
    private $white_list = [
        '/admin/login/index',
        '/admin/login/dologin',
        '/admin/login/captcha',
        '/admin/index/index',
    ];

    /**
     * Admin模块访问权限
     *
     * @param \Yaf_Request_Abstract $request
     * @param \Yaf_Response_Abstract $response
     *
     * @return bool|void
     * @throws Exception
     */
    public function dispatchLoopStartup(\Yaf_Request_Abstract $request, \Yaf_Response_Abstract $response)
    {
        $uri = strtolower($request->getRequestUri());
        $admin_acl = $_SESSION['admin_acl'] ?? [];
        if (!in_array($uri, $this->white_list)) {
            if (!isset($_SESSION['admin_info'])) {
                throw new Exception("您已退出，请先<a onclick='parent.window.location.reload();' style='cursor: pointer;'>登录</a>");
            }

            if (!in_array($uri, $admin_acl)) {
                throw new Exception("您没有<code>{$uri}</code>访问权限");
            }
        }
    }

    protected function setWhiteList($white_list = [])
    {
        $this->white_list = array_merge($this->white_list, $white_list);
    }


}
