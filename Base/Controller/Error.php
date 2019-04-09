<?php
namespace Base\Controller;
    /**
     * 在此做错误和异常统一处理
     *
     *
     * 异常分为
     * controller和validate 参数检查异常和逻辑异常
     * dao
     * data
     * service
     * 以上5处异常信息可以展示给用户，也可以隐藏，返回系统异常
     * S和EXCEPTION    此处异常信息不应该展示给用户，返回系统异常
     *
     *
     * 异常处理
     * 页面的异常通常跳转到错误页面，页面上指出错误信息
     * ajax请求异常给返回json信息
     * api请求异常给返回json信息
     */
use Base\Logger;
use S\Http\Response;

/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class Error extends ControllerAbstract {

    /**
     * 错误页面
     * @param $exception
     */
    public function errorAction(\Exception $exception) {
        Logger::getInstance()->error([
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getTrace(),
            $exception->getTraceAsString(),
            $exception->getLine(),
            $exception->getPrevious(),
        ], 'error');

        $is_not_found = $this->isNotFound($exception->getCode());
        if ($is_not_found) {
            $this->response['code'] = '4040000';
            $this->response['msg'] = '404 Not Found';
        } else {
            $this->response['code'] = $exception->getCode();
            $this->response['msg'] = $exception->getMessage();
        }

        if(\S\Http\Response::getFormatter() == \S\Http\Response::FORMAT_PLAIN){
            $this->response = $this->response['msg'];
        }

    }

    /**
     * 判断错误是否为404错误
     * @return bool
     */
    public function isNotFound($error_code) {
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