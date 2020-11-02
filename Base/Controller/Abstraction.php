<?php
namespace Base\Controller;

use Base\Exception;
use S\Http\Request;
use S\Http\Response;

/**
 * Class WebAbstract
 *
 * 用户端的Controller继承
 */
abstract class Abstraction extends \Yaf_Controller_Abstract {
    protected $response;
    protected $route = 'map';

    public function init() {
        \Yaf_Dispatcher::getInstance()->disableView();
        if(Request::isAjax()) {
            Response::setFormatter(Response::FORMAT_JSON);
        }else{
            Response::setFormatter(Response::FORMAT_HTML);
        }
    }

    /**
     * @param string $action_name
     * @param array $var_array
     * @return mixed|Yaf_Controller_Abstract
     * @throws Exception
     */
    protected function render(string $action_name, array $var_array = []): \Yaf_Controller_Abstract
    {
        switch (Response::getFormatter()){
            case Response::FORMAT_PLAIN:
                echo $this->response ?? '';
                break;
            case Response::FORMAT_JSON:
                $this->displayJson();
                break;
            default:
                $this->displayView();
                break;
        }
    }

    public function displayJson() {
        $data['code'] = $this->response['code'] ?? 2000000;
        $data['msg'] = $this->response['msg'] ?? 'success';
        unset($this->response['code'], $this->response['msg']);

        if(isset($this->response['data'])){
            $data['data']  = $this->response['data'];
        } elseif ($this->response){
            $data['data']  = $this->response;
        }

        @header('Content-type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * @throws Exception
     */
    public function displayView() {
        $tpl_path = $this->getTplPath();

        if(defined(USE_SMARTY) && USE_SMARTY) {
            $smarty = new \Smarty();
            $smarty->left_delimiter = '{{';
            $smarty->right_delimiter = '}}';
            $smarty->setTemplateDir($this->getViewpath());
            $smarty->setCompileDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/compile');
            $smarty->setCacheDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/cache');
            $smarty->caching = false;//\Smarty::CACHING_LIFETIME_CURRENT;
            //$smarty->cache_lifetime = 5;
            foreach ($this->response as $key => $value) {
                $smarty->assign($key, $value);
            }
            try {
                $smarty->display($tpl_path);
            } catch (\SmartyException $e) {
                throw new Exception($e->getMessage(), 5001404);
            }
        } else {
            $this->initView();
            $this->display($tpl_path, $this->response);
        }
    }

    public function getTplPath(){
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        if ($this->route == 'map') {
            $tpl_path = str_replace('_', DIRECTORY_SEPARATOR, strtolower($controller)) . '.html';
        } else {
            $tpl_path = str_replace('_', DIRECTORY_SEPARATOR, strtolower($controller)) . DIRECTORY_SEPARATOR . strtolower($action) . '.html';
        }

        return $tpl_path;
    }
}