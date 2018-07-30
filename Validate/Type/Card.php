<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

/**
 * 卡号验证，支持16到19位卡号
 * Class Card
 * @package S\Validate\Type
 */
class Card extends \Validate\Abstraction {
    public function action($param) {
        if (preg_match('/^[0-9]{9,23}$/', $param['value'])) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }
}
