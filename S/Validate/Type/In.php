<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class In extends Abstraction {

    public function action($param) {
        if (in_array($param['value'], $param['in_list'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }

}