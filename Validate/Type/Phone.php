<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Phone extends \Validate\Abstraction {
    public function action($param) {
        if (preg_match('/^1[3456789]\d{9}$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}