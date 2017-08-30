<?php
namespace Validate\Type;

use Base\Exception;

/**
 * 卡号验证，支持16到19位卡号
 * Class Card
 * @package S\Validate\Type
 */
class Card extends \Validate\Abstraction {
    public function action($param) {
        if (preg_match('/^[0-9]{9,23}$/', $param['value'])) {
            return $param['value'];
        }
        if ($param['msg']) {
            throw new Exception($param['msg']);
        } else {
            throw new Exception("参数格式错误", 5001001);
        }
    }
}
