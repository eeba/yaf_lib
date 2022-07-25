<?php

namespace Base\Controller;

class ErrorController extends Abstraction
{
    public function errorAction($exception)
    {
        \Log\Logger::error("errorAction", [
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getTrace(),
            $exception->getTraceAsString(),
            $exception->getLine(),
            $exception->getPrevious(),
        ]);

        $is_not_found = $this->isNotFound($exception->getCode());
        if ($is_not_found) {
            $this->res['code'] = '4040000';
            $this->res['msg'] = '404 Not Found';
        } else {
            $this->res['code'] = $exception->getCode();
            $this->res['msg'] = $exception->getMessage();
        }

        if(\Base\Response::getFormatter() == \Base\Response::FORMAT_PLAIN){
            $this->res = $this->res['msg'];
        }

        $this->flush();
    }

    /**
     * 判断错误是否为404错误
     *
     * @param $error_code
     * @return bool
     */
    protected function isNotFound($error_code)
    {
        switch ($error_code) {
            case 515://YAF_ERR_NOTFOUND_MODULE
            case 516://YAF_ERR_NOTFOUND_CONTROLLER
            case 517://YAF_ERR_NOTFOUND_ACTION
                return true;
            default:
                return false;
        }
    }
}