<?php
namespace Captcha;

use Gregwar\Captcha\CaptchaBuilder;
use Util\Rand;

class Image {
    public static function show($id = '') {
        header('Content-type: image/jpeg');
        $rand = Rand::human();
        CaptchaBuilder::create($rand)->build()->output();
        $_SESSION['verify_code' . $id] = strtolower($rand);
    }

    public static function verify($code, $id = '') {
        return $code == $_SESSION['verify_code'] . $id;
    }
}