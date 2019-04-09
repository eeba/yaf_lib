<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Email extends Abstraction {
    public function action($param) {

        if (filter_var($param['value'], FILTER_VALIDATE_EMAIL)) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}