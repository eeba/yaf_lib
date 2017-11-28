<?php
namespace Validate\Type;

use Base\Exception;

/**
 * 验证url
 */
class Url extends \Validate\Abstraction {
    public function action($param) {
        $parse = parse_url($param['value']);
        if (!$parse || !isset($parse['host']) || !in_array(strtolower($parse['scheme']), array('ftp','http', 'https'))) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }

        return $param['value'];
    }
}