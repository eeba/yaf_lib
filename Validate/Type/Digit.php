<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class Digit extends \Validate\Abstraction {
    public function action($param) {
        if (is_numeric($param['value'])) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }
}