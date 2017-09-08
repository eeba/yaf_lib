<?php
namespace Base;

class Exception extends \Exception {

    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        $msg = Config::get($message);
        $message = isset($msg['user_msg']) ? $msg['user_msg'] : $message;
        $code = isset($msg['code']) ? $msg['code'] : $code;
        parent::__construct($message, $code, $previous);
    }
}