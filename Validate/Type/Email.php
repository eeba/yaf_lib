<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Email extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($value)
    {

        if (filter_var($value['value'], FILTER_VALIDATE_EMAIL)) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}