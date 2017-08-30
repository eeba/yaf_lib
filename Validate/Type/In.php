<?php
namespace Validate\Type;

use Base\Exception;

class In extends \Validate\Abstraction {

    public function action($param) {
        if (in_array($param['value'], $param['in_list'])) {
            return $param['value'];
        }
        if ($param['msg']) {
            throw new Exception($param['msg']);
        } else {
            throw new Exception("参数格式错误", 5001001);
        }
    }

}