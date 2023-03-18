<?php

namespace Base\Controller;

use Log\Logger;

class ErrorController extends Abstraction
{
    public function errorAction($exception)
    {
        Logger::error("errorAction", [
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

        if (\Base\Response::getFormatter() == \Base\Response::FORMAT_PLAIN) {
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
        return match ($error_code) {
            515, 516, 517 => true,
            default => false,
        };
    }
}