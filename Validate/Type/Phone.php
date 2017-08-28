<?php
namespace Validate\Type;

use Base\Exception;

class Phone extends \Validate\Abstraction {
    public function action($param) {
        if (!preg_match('/^1[345789]\d{9}$/', $param['value'])) {
            throw new Exception($param['msg']);
        }
        return $param['value'];
    }
}