<?php
namespace Base\Plugin;


use Base\Exception;

class Auth extends \Yaf\Plugin_Abstract{
    private $default_acl = [
        '/admin/login/index',
        '/admin/login/dologin',
        '/admin/login/captcha',
    ];
    /**
     * Admin模块访问权限
     *
     * @param \Yaf\Request_Abstract  $request
     * @param \Yaf\Response_Abstract $response
     *
     * @return bool|void
     * @throws Exception
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $uri = strtolower($request->getRequestUri());
        $admin_acl = isset($_SESSION['admin_acl']) ? $_SESSION['admin_acl'] : [];
        if(!in_array($uri, $admin_acl) && !in_array($uri, $this->default_acl)){
            throw new Exception("您没有<code>{$uri}</code>访问权限");
        }
    }

}
