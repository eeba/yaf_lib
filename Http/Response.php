<?php
namespace Http;

use Base\Exception;
use Base\Logger;

class Response {
    const FORMAT_JSON = 'json';
    const FORMAT_PLAIN = 'plain';
    const FORMAT_HTML = 'html';

    static protected $meta = array();
    static protected $formatter = null;

    public static function setFormatter($format) {
        self::$formatter = $format;
    }

    public static function getFormatter() {
        return self::$formatter ?: self::FORMAT_HTML;
    }


    /**
     * 按json格式输出响应
     *
     * @param string|int $code js的错误代码/行为代码
     * @param string     $message 可选。行为所需文案或者错误详细描述。默认为空。
     * @param mixed      $data 可选。附加数据。
     * @param bool       $return_string 可选。是否返回一个字符串。默认情况将直接输出。
     * @return string|void    取决与$return_string的设置。如果return_string为真，则返回渲染结果的字符串，否则直接输出，返回空
     */
    public static function outJson($code, $message = '', $data = array(), $return_string = false) {
        $json_string = json_encode(array_merge(self::$meta, array(
            'code' => $code,
            'msg' => strval($message),
        ), $data));
        Logger::getInstance('response')->debug([$json_string]);
        if ($return_string) {
            return $json_string;
        } else {
            @header('Content-type: application/json');
            echo $json_string;
            return true;
        }
    }


    /**
     * 直接输出内容
     *
     * @param string $text
     */
    public static function outPlain($text) {
        Logger::getInstance('response')->debug([$text]);
        if ($text) {
            echo $text;
        }
    }

    public function cacheHeader($expires) {
        if ($expires === false) {
            return self::header(array(
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Cache-Control' => array(
                    'no-store, no-cache, must-revalidate',
                    'post-check=0, pre-check=0',
                    'max-age=0'
                ),
                'Pragma' => 'no-cache'
            ));
        }
        $expires = is_int($expires) ? $expires : strtotime($expires);

        return self::header(array(
            'Expires' => gmdate('D, d M Y H:i:s', $expires) . ' GMT',
            'Cache-Control' => 'max-age=' . ($expires - time()),
            'Pragma' => 'cache'
        ));
    }

    public static function p3p() {
        @header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
    }

    public static function header(array $headers, $replace = false) {
        foreach ($headers as $key => $value) {
            @header("{$key}:{$value}", $replace);
        }
        return true;
    }

    public static function rawHttpBuildQuery(array $query) {
        return str_replace(array('~', '+'), array('%7E', '%20'), http_build_query($query));
    }

    /**
     * @param string $file 下载文件的内容 支持使用文件的绝对路径来获取
     * @param string $filename 下载文件的名称
     * @throws Exception
     */
    public static function download($file, $filename) {
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