<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Digit extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (is_numeric($value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}