<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Email extends \Validate\Abstraction {
    public function action($param) {

        if (filter_var($param['value'], FILTER_VALIDATE_EMAIL)) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}