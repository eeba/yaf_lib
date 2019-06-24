<?php
namespace Base\Controller;

use Base\Exception;
use S\Http\Response;

/**
 * Class WebAbstract
 *
 * 用户端的Controller继承
 */
abstract class ControllerAbstract extends \Yaf\Controller_Abstract {

    protected $response = [];
    protected $route = 'map';

    public function getParam($key, $default='',$type='request'){
        switch (strtolower($type)) {
            case 'request':
                $ret = htmlspecialchars(\S\Http\Request::request($key, $default));
                break;
            case 'cookie':
                $ret = htmlspecialchars(\S\Http\Request::cookie($key, $default));
                break;
            default:
                $ret = '';
        }
        return $ret;
    }


    protected function render($tpl, array $parameters = null) {
        if (Response::getFormatter() === Response::FORMAT_PLAIN) {
            $this->response = !$this->response ? '' : $this->response;
            Response::outPlain($this->response);
        } elseif (Response::getFormatter() === Response::FORMAT_JSON) {
            $this->displayJson($this->response);
        } else {
            $this->displayView($this->response);
        }
    }

    public function displayJson($data) {
        $msg = isset($data['msg']) ?$data['msg']: 'success';
        $code = isset($data['code']) ?$data['code']: 2000000;
        unset($data['code'], $data['msg']);
        if(isset($data['data'])) {
            $params = $data;
        } elseif ($data) {
            $params['data'] = $data;
        } else {
            $params = [];
        }

        Response::outJson($code, $msg, $params);
    }

    /**
     * @param $tpl_vars
     *
     * @throws Exception
     */
    public function displayView($tpl_vars) {

        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        if ($this->route == 'map') {
            $tpl_path = str_replace('_', DIRECTORY_SEPARATOR, strtolower($controller)) . '.html';
        } else {
            $tpl_path = str_replace('_', DIRECTORY_SEPARATOR, strtolower($controller)) . DIRECTORY_SEPARATOR . strtolower($action) . '.html';
        }
        $smarty = new \Smarty();
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';
        $smarty->setTemplateDir($this->getViewpath());
        $smarty->setCompileDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/compile');
        $smarty->setCacheDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/cache');
        $smarty->caching = false;//\Smarty::CACHING_LIFETIME_CURRENT;
        //$smarty->cache_lifetime = 5;
        foreach ($tpl_vars as $key => $value) {
            $smarty->assign($key, $value);
        }
        try {
            $smarty->display($tpl_path);
        } catch (\SmartyException $e) {
            throw new Exception($e->getMessage(), 5001404);
        }
    }
}