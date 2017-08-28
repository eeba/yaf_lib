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
            throw new Exception($param['msg']);
        }

        return trim($param['value']);
    }
}