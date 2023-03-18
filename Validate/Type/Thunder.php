<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Thunder extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($param)
    {
        if (preg_match('/^thunderx?:\/\/[a-zA-Z\d]+=$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}