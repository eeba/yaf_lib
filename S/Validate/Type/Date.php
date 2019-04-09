<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

/**
 * 验证日期格式为：2014-01-02这种格式
 * Class Date
 * @package Validator
 */
class Date extends Abstraction {
    public function action($param) {
        $value = trim($param['value']);
        if ($value && preg_match('/^\d{4}-\d{2}-\d{2}$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}