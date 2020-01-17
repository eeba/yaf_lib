<?php
namespace Api;

use Base\Config;
use Base\Logger;
use function GuzzleHttp\Psr7\build_query;

class File{
    const UPLOAD_PATH = '/file/upload';
    const DOWNLOAD_PATH = '/file/download';

    /**
     * @param        $file_path
     * @param        $category
     * @param string $filename
     * @param int    $timeout
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($file_path, $category, $filename, $timeout = 30){
        $options = array(
            'multipart' => array(
                array(
                    'name'     => 'file',
                    'contents' => fopen($file_path, 'r'),
                    'filename' => $filename,
                )
            )
        );

        try{
            $ret = (new Util())->request('post', self::UPLOAD_PATH."?category={$category}&filename={$filename}", [], $options, $timeout);
            return $ret['index'];
        }catch (\Exception $e){
            Logger::getInstance()->error([$e->getCode(), $e->getMessage()]);
        }
        return false;
    }

    /**
     * @param $index
     * @param int $timeout
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download($index, $timeout = 30){
        $params = ['index' => $index];
        try {
            return (new Util())->request('get', self::DOWNLOAD_PATH, $params, [], $timeout);
        }catch (\Exception $e){
            Logger::getInstance()->error([$e->getCode(), $e->getMessage()]);
        }
        return false;
    }


    /**
     * 直接显示url
     * @param $index
     * @return string
     */
    public function downloadUrl($index){
        $util = new Util();
        $timestamp  = time();
        $get_params = array(
            "key"    => Config::get(Util::APP_KEY),
            "t"      => $timestamp,
            "m"      => $util->getSign($timestamp, ['index' => $index]),
            "index"  => $index
        );

        $host = Config::get(Util::HOST);

        $uri = self::DOWNLOAD_PATH . '?' . build_query($get_params);

        return $host . $uri;
    }
}