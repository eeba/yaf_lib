<?php
namespace Common;

use Base\Logger;
use Http\Response;
use Http\Request;
use Base\Env;

/**
 * Class Common
 *
 * @package Controller
 * @description 主模块控制器基类
 *              app/controllers目录下所有Controller的基类, 所有主模块中控制器均需要继承此类
 */
abstract class Controller extends \Yaf\Controller_Abstract {

    protected $response = array();
    protected $route = 'map';


    public function init() {
        Env::init($this->getRequest());
        Logger::getInstance('request')->debug(['get' => $_GET, 'post' => $_POST, 'cookie' => $_COOKIE, 'session' => $_SESSION]);
    }

    public function getParams($key, $type = 'request') {
        switch (strtolower($type)) {
            case 'request':
                $ret = Request::request($key);
                break;
            case 'cookie':
                $ret = Request::cookie($key);
                break;
            default:
                $ret = '';
        }
        return $ret;
    }

    public function render($tpl = '', array $parameters = []) {
        if (Response::getFormatter() === Response::FORMAT_PLAIN) {
            Response::outPlain($this->response);
        } elseif (Response::getFormatter() === Response::FORMAT_JSON) {
            $this->displayJson($this->response);
        } else {
            $this->displayView($this->response);
        }
    }

    public function displayJson($data) {
        $msg = isset($data['msg']) ?: 'success';
        $code = isset($data['code']) ?: '2000000';
        Response::outJson($code, $msg, $data);
    }

    public function displayView($tpl_vars) {

        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        if ($this->route == 'map') {
            $tpl_path = str_replace('_', DS, strtolower($controller)) . '.html';
        } else {
            $tpl_path = str_replace('_', DS, strtolower($controller)) . DS . strtolower($action) . '.html';
        }
        $smarty = new \Smarty();
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';
        $smarty->setTemplateDir($this->getViewpath());
        $smarty->setCompileDir(ROOT_PATH . DS . 'data/compile');
        $smarty->setCacheDir(ROOT_PATH . DS . 'data/cache');
        $smarty->caching = false;//\Smarty::CACHING_LIFETIME_CURRENT;
        //$smarty->cache_lifetime = 5;
        foreach ($tpl_vars as $key => $value) {
            $smarty->assign($key, $value);
        }
        try {
            $smarty->display($tpl_path);
        } catch (\SmartyCompilerException $e) {
            $smarty->assign('code', $e->getCode());
            $smarty->assign('msg', $e->getMessage());
            $smarty->display($tpl_path);
        }
    }
}