<?php
namespace Validate\Type;

use Base\Exception;

/**
 * 验证日期格式为：2014-01-02这种格式
 * Class Date
 * @package Validator
 */
class Date extends \Validate\Abstraction {
    public function action($param) {
        $value = trim($param['value']);
        if (!$value || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $param['value'])) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }
        return $param['value'];
    }
}