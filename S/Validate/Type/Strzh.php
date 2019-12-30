<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Strzh extends Abstraction {

    public function action($param) {
        if (preg_match('/^(?:[\u4e00-\u9fa5·]{2,16})$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}