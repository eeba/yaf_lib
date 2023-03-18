<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Digit extends Abstraction
{
    /**
     * @throws Exception
     */
    public function action(array $param)
    {
        if (is_numeric($param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}