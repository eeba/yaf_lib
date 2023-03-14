<?php

namespace Base\Controller;

use Base\Request;
use Base\Response;
use Base\View;
use Yaf_Dispatcher;

class Abstraction extends \Yaf_Controller_Abstract
{
    protected $res = [];

    protected function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
        if (!Response::getFormatter()) {
            if (Request::isAjax()) {
                Response::setFormatter(Response::FORMAT_JSON);
            } else {
                Response::setFormatter(Response::FORMAT_HTML);
            }
        }
    }

    public function flush()
    {
        switch (Response::getFormatter()) {
            case Response::FORMAT_HTML:
                Yaf_Dispatcher::getInstance()->enableView();
                Yaf_Dispatcher::getInstance()->setView(new View());
                foreach ($this->res as $key => $value) {
                    $this->getView()->assign($key, $value);
                }
                break;
            case Response::FORMAT_JSON:
                $data['code'] = $this->res['code'] ?? 2000000;
                $data['msg'] = $this->res['msg'] ?? 'success';
                unset($this->res['code'], $this->res['msg']);
                $data = array_merge($data, $this->res);
                $this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8');
                $this->getResponse()->setBody(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                break;
            case Response::FORMAT_PLAIN:
                $this->getResponse()->setHeader('Content-Type', 'text/plain; charset=utf-8');
                $this->getResponse()->setBody(strval($this->res));
                break;
            default:
                $this->getResponse()->setHeader('Content-Type', 'text/plain; charset=utf-8');
                $this->getResponse()->setBody("很明显出错了.");
        }
    }
}