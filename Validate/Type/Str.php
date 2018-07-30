<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Str extends \Validate\Abstraction {
    protected $default_settings = array(
        'min' => 0,
        'max' => 255,
    );

    public function action($param) {
        $len = strlen($param['value']);
        $min = isset($param['option']['min']) ? $param['option']['min'] : $this->default_settings['min'];
        $max = isset($param['option']['max']) ? $param['option']['max'] : $this->default_settings['max'];
        if ($len >= $min && $len <= $max) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }
}