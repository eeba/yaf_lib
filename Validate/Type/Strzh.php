<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Strzh extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^([\x{4e00}-\x{9fa5}·]{2,16})$/u', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}