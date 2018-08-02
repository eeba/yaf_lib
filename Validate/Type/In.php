<?php
namespace Validate\Type;

use Base\Exception;
use Base\Config;

class In extends \Validate\Abstraction {

    public function action($param) {
        if (in_array($param['value'], $param['in_list'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }

}