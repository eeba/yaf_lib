<?php
namespace Validate\Type;

use Base\Exception;

class Ip extends \Validate\Abstraction {
    public function action($param) {
        $ret = filter_var($param['value'], FILTER_VALIDATE_IP);
        if ($ret === false) {
            throw new Exception($param['msg']);
        }
        return $param['value'];
    }
}