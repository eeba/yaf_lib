<?php
namespace Validate\Type;

use Base\Exception;

class Ip extends \Validate\Abstraction {
    public function action($param) {
        $ret = filter_var($param['value'], FILTER_VALIDATE_IP);
        if ($ret === false) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }
        return $param['value'];
    }
}