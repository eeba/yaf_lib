<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Ed2k extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($param)
    {
        if (preg_match('/^ed2k:\/\/\|file\|.+\|\/$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}