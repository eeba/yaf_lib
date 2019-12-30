<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Thunder extends Abstraction {

    public function action($param) {
        if (preg_match('/^thunderx?:\/\/[a-zA-Z\d]+=$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}