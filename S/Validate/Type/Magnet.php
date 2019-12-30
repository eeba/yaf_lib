<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

class Magnet extends Abstraction {

    public function action($param) {
        if (preg_match('/^magnet:\?xt=urn:btih:[0-9a-fA-F]{40,}.*$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}