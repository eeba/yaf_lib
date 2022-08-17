<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Ip extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        $ret = filter_var($value['value'], FILTER_VALIDATE_IP);
        if ($ret) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}