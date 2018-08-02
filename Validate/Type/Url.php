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

        throw new Exception($param['msg']);
    }
}