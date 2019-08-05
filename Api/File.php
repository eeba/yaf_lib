<?php
namespace Api;

class File{
    const UPLOAD_PATH = '/file/upload';
    const DOWNLOAD_PATH = '/file/download';

    /**
     * @param        $category
     * @param        $file_path
     * @param string $filename
     * @param int    $timeout
     *
     * @return string
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upload($file_path, $category, $filename, $timeout = 30){
        $params = array(
                    array(
                        'name'     => 'file',
                        'contents' => fopen($file_path, 'r'),
                        'filename' => $filename,
                    )
                );

        $options = array(
            'headers'=>array(
                'timeout'   => $timeout,
            )
        );

        $ret = (new Util())->request(self::UPLOAD_PATH."?category={$category}&filename={$filename}", $params, $options, true, $timeout);
        return $ret['index'];
    }
}