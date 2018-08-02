<?php
namespace Base;

class Exception extends \Exception {

    public function __construct($message = "", $code = 0, \Exception $previous = null) {

        $error_msg = $message;
        $error_code = 5001000;
        list($error) = explode('.', $message);
        if (in_array($error, ['error', 'validate'])) {
            $conf = Config::get($message);
            $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: $message;
            $error_code = $conf['code'] ?: 5001000;
        }

        parent::__construct($error_msg, $error_code, $previous);
    }
}