<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

/**
 * 验证url
 */
class Url extends \Validate\Abstraction {
    public function action($param) {
        $parse = parse_url($param['value']);
        if ($parse && isset($parse['host']) && in_array(strtolower($parse['scheme']), array('ftp','http', 'https'))) {
            return $param['value'];
        }

        $conf = Config::get($param['msg']);
        $error_msg = ($conf['user_msg'] ?: $conf['sys_msg']) ?: "参数格式错误";
        $error_code = $conf['code'] ?: 5001001;
        throw new Exception($error_msg, $error_code);
    }
}