<?php
namespace Base;

class Exception extends \Exception {

    public function __construct($message = "", $code = 5001001, \Exception $previous = null) {

        list($error) = explode('.', $message);
        if (in_array($error, ['error', 'validate'])) {
            $conf = Config::get($message);
            $message = $conf['msg'] ?: $message;
            $code = $conf['code'] ?: $code;
        }

        parent::__construct($message, $code, $previous);
    }
}