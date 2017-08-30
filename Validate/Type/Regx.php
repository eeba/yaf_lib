<?php
namespace Validate\Type;

use Base\Exception;

class Regx extends \Validate\Abstraction {


    public function action($param) {
        if (!preg_match($param['rule'], $param['value'])) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }
        return $param['value'];
    }
}