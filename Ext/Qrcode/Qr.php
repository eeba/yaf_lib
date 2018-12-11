<?php
namespace Ext\Qrcode;

use Endroid\QrCode\ErrorCorrectionLevel as QrErrorCorrectionLevel;
use Endroid\QrCode\QrCode as QrCode;
use Http\Response;

class Qr {

    /**
     * php 版本要求 >= 7.1
     *
     *
     * @param $text 二维码内容
     * @param int $size 大小（像素）
     * @param array $color 二维码颜色
     * @param boolean $base64 返回true:base64, false:直接输出
     * @return string
     */
    public static function create($text, $size = 300, $color = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0], $base64 = true){
        $logo_size = $size/4;

        // Create a basic QR code
        $qrCode = new QrCode($text);
        $qrCode->setSize($size);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin($size/30);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(QrErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor($color);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        //logo
        //$qrCode->setLogoPath('/tmp/xxx.png');
        //$qrCode->setLogoWidth($logo_size);

        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);

        if($base64){
            return $qrCode->writeDataUri();
        }else{
            Response::setFormatter(Response::FORMAT_PLAIN);
            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();
        }
    }

}