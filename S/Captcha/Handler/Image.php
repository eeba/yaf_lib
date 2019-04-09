<?php
namespace S\Captcha\Handler;

use Gregwar\Captcha\CaptchaBuilder;
use S\Http\Response;
use Util\Rand;

class Image extends Abstraction {

    /**
     * @param bool   $type
     * @param string $id
     *
     * @return mixed
     */
    public function show($type = false, $id = '') {
        $rand = Rand::human();
        $_SESSION['verify_code' . $id] = strtolower($rand);

        if($type) {
            return CaptchaBuilder::create($rand)->build()->inline();
        }

        Response::setFormatter(Response::FORMAT_PLAIN);
        header('Content-type: image/jpeg');
        CaptchaBuilder::create($rand)->build()->output();

        return true;
    }

    public function verify($code, $id = '') {
        return $code == $_SESSION['verify_code'] . $id;
    }
}