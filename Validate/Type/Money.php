<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Money extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^[0-9]+$/', $value['value'])) {
            return $value['value'];
        } elseif (preg_match('/^[0-9]+\.[0-9]+$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}