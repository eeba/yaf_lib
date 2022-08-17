<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Ed2k extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^ed2k:\/\/\|file\|.+\|\/$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}