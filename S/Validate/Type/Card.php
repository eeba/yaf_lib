<?php
namespace S\Validate\Type;

use Base\Exception;
use S\Validate\Abstraction;

/**
 * 卡号验证，支持16到19位卡号
 * Class Card
 * @package S\Validate\Type
 */
class Card extends Abstraction {
    public function action($param) {
        if (preg_match('/^[0-9]{9,23}$/', $param['value'])) {
            return $param['value'];
        }

        throw new Exception($param['msg']);
    }
}
