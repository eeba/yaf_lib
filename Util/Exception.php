<?php

namespace Base\Util;

/**
 * Class Exception
 *
 * @package     Base\Util
 * @description 异常工具类
 */
class Exception {

    /**
     * 获取异常或错误的详细栈信息
     *
     * @param \Throwable $e 可抛出异常, 包括系统或自定义的异常或错误
     *
     * @return string 详细异常或错误栈信息
     */
    public static function getFullTraceAsString(\Throwable $e) {
        $trace = $e->getTrace();

        $trace_str = '';
        foreach ($trace as $count => $item) {
            if (isset($item['file'])) {
                $current_file = $item['file'];
            } else {
                $current_file = '[internal function]';
            }
            if (isset($item['line'])) {
                $current_line = $item['line'];
            } else {
                $current_line = '';
            }
            if (empty($item['args'])) {
                $params_str = '';
            } else {
                $params_str = self::getParamStr($item['args']);
            }
            $trace_str .= sprintf('#%s %s%s: %s%s%s(%s)' . PHP_EOL,
                $count,
                $current_file,
                $current_line ? "($current_line)" : '',
                $item['class'],
                $item['type'],
                $item['function'],
                $params_str
            );
        }
        $trace_str .= "#" . count($trace) . " {main}";

        return $trace_str;
    }

    /**
     * 获取参数列表字符串表示
     *
     * @param array $params 参数列表
     *
     * @return string 格式化参数列表, e.g. 'a', NULL, array(1, true, array(Object(\NS\Class), Resource(curl)))
     */
    private static function getParamStr(array $params) {
        $str = '';
        foreach ($params as $param) {
            if (is_string($param)) {
                $str .= "'{$param}'";
            } else if (is_array($param)) {
                $str .= 'array(' . self::getParamStr($param) . ')';
            } else if (is_null($param)) {
                $str .= 'NULL';
            } else if (is_bool($param)) {
                $str .= (($param) ? "true" : "false");
            } else if (is_object($param)) {
                $str .= 'Object(' . get_class($param) . ')';
            } else if (is_resource($param)) {
                $str .= 'Resource(' . get_resource_type($param) . ')';
            } else {
                $str .= $param;
            }

            $str .= ', ';
        }

        return trim($str, ', ');
    }

}