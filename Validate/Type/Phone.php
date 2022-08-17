<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Phone extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^1\d{10}$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}