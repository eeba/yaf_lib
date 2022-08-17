<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Magnet extends Abstraction
{

    /**
     * @throws Exception
     */
    public function action($value)
    {
        if (preg_match('/^magnet:\?xt=urn:btih:[0-9a-fA-F]{40,}.*$/', $value['value'])) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}