<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

/**
 * 验证日期格式为：2014-01-02这种格式
 * Class Date
 * @package Validator
 */
class Date extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($value)
    {
        $value = trim($value['value']);
        if ($value && preg_match('/^\d{4}([\-\/\.]?)\d{2}\1\d{2}\s*(\d{2}:\d{2}:\d{2})?$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}