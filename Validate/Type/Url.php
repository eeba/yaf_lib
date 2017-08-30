<?php
namespace Validate\Type;

use Base\Exception;

/**
 * 验证url
 */
class Url extends \Validate\Abstraction {
    protected $_default_settings = array(
        'filter_xss' => true,
        'is_trusted' => false,   // 是否为可信的回跳地址
        'trusted_domains' => array(), // 自定义的一些可信域
    );

    public function action($param) {
        $result = filter_var($param['value'], FILTER_VALIDATE_URL);
        if (!$result) {
            if ($param['msg']) {
                throw new Exception($param['msg']);
            } else {
                throw new Exception("参数格式错误", 5001001);
            }
        }

        return $param['value'];
    }

    public function filter_xss($url) {
        $illegal_chars = array('\'', '"', ';', '<', '>', '(', ')', '{', '}', '[', ']');
        $encoded_chars = array('%27', '%22', '%3B', '%3C', '%3E', '%28', '%29', '%7B', '%7D', '%5B', '%5D');
        return str_replace($illegal_chars, $encoded_chars, $url);
    }
}