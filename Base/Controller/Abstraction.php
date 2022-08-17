<?php

namespace Base\Controller;

use Base\Response;
use Base\View;

class Abstraction extends \Yaf_Controller_Abstract
{
    protected $res = [];

    protected function init()
    {
        \Yaf_Dispatcher::getInstance()->disableView();
        if (!Response::getFormatter()) {
            if ($this->isAjax()) {
                Response::setFormatter(Response::FORMAT_JSON);
            } else {
                Response::setFormatter(Response::FORMAT_HTML);
            }
        }
    }

    protected function isAjax()
    {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * 匹配出输模式输出
     */
    protected function flush()
    {
        if (Response::getFormatter() == Response::FORMAT_HTML) {
            \Yaf_Dispatcher::getInstance()->enableView();
            \Yaf_Dispatcher::getInstance()->setView(new View());
            if ($this->res) {
                foreach ($this->res as $key => $value) {
                    $this->getView()->assign($key, $value);
                }
            }
        } elseif (Response::getFormatter() == Response::FORMAT_JSON) {
            $data['code'] = $this->res['code'] ?? 2000000;
            $data['msg'] = $this->res['msg'] ?? 'success';
            unset($this->res['code'], $this->res['msg']);
            $data = array_merge($data, $this->res);

            $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
            $this->response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $this->response->setHeader('Content-Type', 'text/plain; charset=utf-8');
            $this->response->setBody(strval($this->res));
        }
    }
}