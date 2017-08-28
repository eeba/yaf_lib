<?php
namespace Validate;


/**
 * Validator抽象类
 * 子类需要实现用于验证的action方法
 *
 * <code>
 *  $validator = new \Validate\Type\Email();
 *  $email = $validator->check('s@sina.cn');
 * </code>
 */
abstract class Abstraction {
    /**
     * 验证方法
     *
     * @param $value
     * @return mixed
     */
    abstract public function action($value);
}
