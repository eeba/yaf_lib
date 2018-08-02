<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Regx extends \Validate\Abstraction {


    public function action($param) {
        if (preg_match($param['rule'], $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}