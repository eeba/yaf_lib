<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

/**
 * 卡号验证，支持16到19位卡号
 * Class Card
 * @package Validate\Type
 */
class Card extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^[0-9]{9,23}$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}
