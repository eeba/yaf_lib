<?php

namespace Base;

use Base\Exception;

class Response extends \Yaf_Response_Http
{
    const FORMAT_JSON = 'json';
    const FORMAT_PLAIN = 'plain';
    const FORMAT_HTML = 'html';

    static public $formatter = null;

    public static function setFormatter($format)
    {
        self::$formatter = $format;
    }

    public static function getFormatter()
    {
        return self::$formatter ?: '';
    }

    /**
     * @param string $file 下载文件的内容 支持使用文件的绝对路径来获取
     * @param string $filename 下载文件的名称
     * @throws Exception
     */
    public static function download($file, $filename)
    {
        ob_clean();
        ob_start();

        //清除$file中的\0 防止php_warning
        $file_cleaned = strval(str_replace("\0", "", $file));
        if (is_file($file_cleaned)) {
            if (!is_readable($file_cleaned)) {
                ob_end_clean();
                throw new Exception("该文件不可读", 5002101);
            }
            $length = filesize($file);
            readfile($file);
        } else {
            if (!$file) {
                ob_end_clean();
                throw new Exception("文件内容为空", 5002102);
            }
            $length = strlen($file);
            echo $file;
        }

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename");
        header('Content-Length: ' . $length);
        header("Pragma: no-cache");
        header("Expires: 0");
        ob_end_flush();
    }
}