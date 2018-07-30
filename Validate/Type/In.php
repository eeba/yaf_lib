<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class In extends \Validate\Abstraction {

    public function action($param) {
        if (in_array($param['value'], $param['in_list'])) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }

}