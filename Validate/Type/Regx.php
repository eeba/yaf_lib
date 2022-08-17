<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Regx extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match($value['rule'], $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}