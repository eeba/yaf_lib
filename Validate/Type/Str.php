<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Str extends Abstraction
{
    protected $default_settings = array(
        'min' => 0,
        'max' => 255,
    );

    /**
     * @throws Exception
     */
    public function action($value)
    {
        $len = strlen($value['value']);
        $min = $value['option']['min'] ?? $this->default_settings['min'];
        $max = $value['option']['max'] ?? $this->default_settings['max'];
        if ($len >= $min && $len <= $max) {
            return $value['value'];
        }

        throw new Exception($value['msg']);
    }
}