<?php

namespace Validate\Type;

use Base\Exception;
use Validate\Abstraction;

class Str extends Abstraction
{
    protected array $default_settings = array(
        'min' => 0,
        'max' => 255,
    );

    /**
     * @throws Exception
     */
    public function action($param)
    {
        $len = strlen($param['value']);
        $min = $param['option']['min'] ?? $this->default_settings['min'];
        $max = $param['option']['max'] ?? $this->default_settings['max'];
        if ($len >= $min && $len <= $max) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}