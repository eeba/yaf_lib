<?php
namespace Validate\Type;

use Base\Exception;

class Phone extends \Validate\Abstraction {
    public function action($param) {
        if (!preg_match('/^1[345789]\d{9}$/', $param['value'])) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }
        return $param['value'];
    }
}