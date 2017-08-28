<?php
namespace Validate\Type;

use Base\Exception;

class In extends \Validate\Abstraction {

    public function action($param) {
        if (in_array($param['value'], $param['option']['in'])) {
            return $param['value'];
        }
        throw new Exception($param['msg']);
    }

}