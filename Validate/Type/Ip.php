<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Ip extends Abstraction
{

    public function action($param)
    {
        $ret = filter_var($param['value'], FILTER_VALIDATE_IP);
        if ($ret != false) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}