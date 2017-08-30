<?php
namespace Validate\Type;

use Base\Exception;

class Digit extends \Validate\Abstraction {
    public function action($param) {
        if (is_numeric($param['value'])) {
            return $param['value'];
        }

        if ($param['msg']) {
            throw new Exception($param['msg']);
        } else {
            throw new Exception("参数格式错误", 5001001);
        }
    }
}