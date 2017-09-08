<?php
namespace Controller;

use Http\Response;
use Http\Request;

/**
 * Class Common
 *
 * @package Base\Controller
 * @description 主模块控制器基类
 *              app/controllers目录下所有Controller的基类, 所有主模块中控制器均需要继承此类
 */
abstract class Common extends \Yaf\Controller_Abstract {

    protected $response;

    public function init() {
        if (Request::isAjax()) {
            Response::setFormatter(Response::FORMAT_JSON);
        }
    }

    public function getParams($key, $type = 'request') {
        switch (strtolower($type)) {
            case 'request':
                $ret = strip_tags(Request::request($key));
                break;
            case 'cookie':
                $ret = strip_tags(Request::cookie($key));
                break;
            default:
                $ret = '';
        }
        return $ret;
    }

    public function render($tpl = '', array $parameters = null) {
        if (Response::getFormatter() === Response::FORMAT_PLAIN) {
            Response::outPlain($this->response);
        } elseif (Response::getFormatter() === Response::FORMAT_JSON) {
            $this->displayJson($this->response);
        } else {
            $this->displayView($this->response);
        }
    }

    public function displayJson($data) {
        Response::outJson(2000000, 'success', $data);
    }

    public function displayView($tpl_vars) {
        $tpl_name = $this->getRequest()->action;
        $this->display($tpl_name, $tpl_vars);
    }
}
