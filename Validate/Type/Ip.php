<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Ip extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($param)
    {
        $ret = filter_var($param['value'], FILTER_VALIDATE_IP);
        if ($ret) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}