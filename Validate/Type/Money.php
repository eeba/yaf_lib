<?php
namespace Validate\Type;

use Base\Exception;

class Money extends \Validate\Abstraction {
    public function action($param) {

        if (preg_match('/^[0-9]*$/', $param['value'])) {
            return $param['value'];
        } elseif (preg_match('/^[0-9]*\.[0-9]*$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}