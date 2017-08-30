<?php
namespace Validate\Type;

use Base\Exception;

class Str extends \Validate\Abstraction {
    protected $_default_settings = array(
        'min' => 1,
        'max' => 255,
    );

    public function action($param) {
        $len = strlen($param['value']);
        $min = $param['option']['min'];
        $max = $param['option']['max'];
        if ($len < $min || $len > $max) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }

        return $param['value'];
    }
}