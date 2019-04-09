<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Digit extends Abstraction {
    public function action($param) {
        if (is_numeric($param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}