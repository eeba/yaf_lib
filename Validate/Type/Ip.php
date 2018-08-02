<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Ip extends \Validate\Abstraction {
    public function action($param) {
        $ret = filter_var($param['value'], FILTER_VALIDATE_IP);
        if ($ret != false) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}