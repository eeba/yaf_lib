<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Thunder extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^thunderx?:\/\/[a-zA-Z\d]+=$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}