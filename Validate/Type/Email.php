<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Email extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action($param)
    {

        if (filter_var($param['value'], FILTER_VALIDATE_EMAIL)) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}