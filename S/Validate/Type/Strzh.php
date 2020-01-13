<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Strzh extends Abstraction {

    public function action($param) {
        if (preg_match('/^([\x{4e00}-\x{9fa5}·]{2,16})$/u', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}