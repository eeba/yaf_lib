<?php
namespace Ext\Captcha;

use Gregwar\Captcha\CaptchaBuilder;
use Util\Rand;

class Image {
    public static function show($id = '') {
        \Http\Response::setFormatter(\Http\Response::FORMAT_PLAIN);
        header('Content-type: image/jpeg');
        $rand = Rand::human();
        CaptchaBuilder::create($rand)->build()->output();
        $_SESSION['verify_code' . $id] = strtolower($rand);
    }

    public static function verify($code, $id = '') {
        return $code == $_SESSION['verify_code'] . $id;
    }
}