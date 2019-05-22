<?php
namespace Api;

class File{
    const UPLOAD_PATH = '/file/upload';
    const DOWNLOAD_PATH = '/file/download';

    /**
     * @param        $category
     * @param        $file
     * @param string $filename
     * @param int    $timeout
     *
     * @return string
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($file, $category, $filename, $timeout = 30){
        $params = array(
                    array(
                        'name'     => 'file',
                        'contents' => fopen($file, 'r'),
                        'filename' => $filename,
                    )
                );

        $options = array(
            'headers'=>array(
                'timeout'   => $timeout,
            )
        );

        $ret = (new Util())->request(self::UPLOAD_PATH."?category={$category}&filename={$filename}", $params, $options, true, $timeout);
        return 'https://api.u7c.cn/file/download?index='.$ret['index'];
    }
}