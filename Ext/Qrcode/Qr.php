<?php
namespace Ext\Qrcode;

use Endroid\QrCode\ErrorCorrectionLevel as QrErrorCorrectionLevel;
use Endroid\QrCode\QrCode as QrCode;

class Qr {

    public static function create($text, $size = 300, $font_color = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]){
        $logo_size = $size/4;

        // Create a basic QR code
        $qrCode = new QrCode($text);
        $qrCode->setSize($size);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin($size/30);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(QrErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor($font_color);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        //logo
        //$qrCode->setLogoPath('/tmp/xxx.png');
        //$qrCode->setLogoWidth($logo_size);

        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);

        //header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeDataUri();
    }

}