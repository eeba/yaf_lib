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
    public function action(array $param)
    {
        if (preg_match('/^[0-9]{9,23}$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}
