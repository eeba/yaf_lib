<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class In extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (in_array($value['value'], $value['in_list'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }

}