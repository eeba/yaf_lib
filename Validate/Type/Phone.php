<?php
namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Phone extends Abstraction {

    public function action($param) {
        if (preg_match('/^1[3456789]\d{9}$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}