<?php

namespace Base;

class Exception extends \Yaf_Exception
{

    public function __construct($message = "", $code = 5001001, \Exception $previous = null)
    {
        list($msg_type) = explode('.', $message);
        if(in_array($msg_type, ['error'])){
            $msg = Config::get('message.' . $message);
            list($code, $message) = explode(':', $msg);
        }
        parent::__construct($message, $code, $previous);
    }
}