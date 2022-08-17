<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

/**
 * 验证url
 */
class Url extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($value)
    {
        $parse = parse_url($value['value']);
        if ($parse && isset($parse['host']) && in_array(strtolower($parse['scheme']), array('ftp', 'http', 'https'))) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}