<?php
namespace Util;

use Endroid\QrCode\ErrorCorrectionLevel as QrErrorCorrectionLevel;
use Endroid\QrCode\QrCode as QrCode;

class Qr {

    public static function create($text, $size = 300, $font_color = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]){
//        $md5 = (new \Data\QrCode\QrCode())->add($text);
//        $content = HOST . '/qrcode/read?m='.$md5;
        $logo_size = $size/4;

        // Create a basic QR code
        $qrCode = new QrCode($text);
        $qrCode->setSize($size);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(QrErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor($font_color);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        //logo
        //$qrCode->setLogoPath('/tmp/xxx.png');
        //$qrCode->setLogoWidth($logo_size);

        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }


//    public static function read($md5) {
//        if (!$md5) {
//            return false;
//        }
//        $info = (new \Data\QrCode\QrCode())->getByMd5($md5);
//        if (!$info) {
//            return false;
//        }
//
//        self::statistics($info['id']);
//
//        $parse = parse_url($info['content']);
//        if (!$parse || !isset($parse['host']) || !in_array(strtolower($parse['scheme']), array('ftp', 'http', 'https'))) {
//            $info['content'];
//        }else{
//            header('status: 302');
//            header("Location: " . $info['content']);
//        }
//
//        return $info['content'];
//    }

    /**
     * 扫码量统计
     */
//    public static function statistics($qr_id){
//        $ip = \Util\Ip::getClientIp();
//        $ua = \Http\Request::server('HTTP_USER_AGENT');
//        (new \Data\QrCode\QrCodeStatistics())->add($qr_id, $ip, $ua);
//    }

}