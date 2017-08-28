<?php
namespace Validate\Type;

use Base\Exception;

class Digit extends \Validate\Abstraction {
    public function action($param) {
        if (is_numeric($param['value'])) {
            return $param['value'];
        }
        throw new Exception($param['msg']);
    }
}