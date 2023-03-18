<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class In extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($param)
    {
        if (in_array($param['value'], $param['in_list'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }

}