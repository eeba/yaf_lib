<?php
namespace Validate\Type;

use Base\Exception;

class Regx extends \Validate\Abstraction {


    public function action($param) {
        if (!preg_match($param['rule'], $param['value'])) {
            throw new Exception($param['msg']);
        }
        return trim($param['value']);
    }
}