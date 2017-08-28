<?php
namespace Validate\Type;

use Base\Exception;

class Email extends \Validate\Abstraction {
    public function action($param) {

        if (!filter_var($param['value'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception($param['msg']);
        }

        return $param['value'];
    }

    /**
     * @param $host
     * @return bool
     */
    private function _mx_exist($host) {
        return getmxrr($host, $arr);
    }
}