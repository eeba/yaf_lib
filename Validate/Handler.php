<?php

namespace Validate;

/**
 * Class Handler
 * @package Validate
 *
 * <code>
 * $params = array(
 *      'id' => array(
 *               'value'  => Request::request('id'),
 *               'rule'   => 'digit',
 *               'msg'    => 'validate.is_not_number',
 *      ),
 *      'type' => array(
 *               'value'  => Request::request('type'),
 *               'rule'   => 'in',
 *               'in_list' => array(1,2,4,5)
 *               'msg'    => 'validate.is_not_number',
 *      )
 *      'loan_id' => array(
 *               'value'  => Request::request('loan_id'),
 *               'rule'   => '/^[a-zA-Z0-9]{1,32}$/',
 *               'msg'    => 'validate.is_not_number',
 *      )
 * )
 * Handler::check($params);
 *
 * </code>
 */
class Handler {

    private static $_default_validator = array(
        'date' => 'Type\\Date',
        'digit' => 'Type\\Digit',
        'email' => 'Type\\Email',
        'in' => 'Type\\In',
        'ip' => 'Type\\Ip',
        'phone' => 'Type\\Phone',
        'str' => 'Type\\Str',
        'url' => 'Type\\Url',
        'money' => 'Type\\Money',
        'card' => 'Type\\Card',
        'identify' => 'Type\\Identify',
    );

    /**
     * 参数检查入口
     * 根据rule确定具体的验证方法，并将value，filter，option作为参数调用该调用
     *
     * @param array $params
     * @return null
     */
    public static function check(array $params) {
        $ret = array();
        foreach ($params as $key => $value) {
            $rule = $value['rule'];
            if ($rule === 'filter') {
                continue;
            }

            if (isset(self::$_default_validator[$rule])) {
                $class = '\\' . __NAMESPACE__ . '\\' . self::$_default_validator[$rule];
            } else {
                $class = '\\' . __NAMESPACE__ . '\\Type\\Regx';
            }
            $tmp_class = new $class();
            $tmp_ret = $tmp_class->action($value);

            $ret[$key] = $tmp_ret;
        }
        return $ret;
    }
}
