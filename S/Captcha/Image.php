<?php

namespace S\Captcha;

use Gregwar\Captcha\CaptchaBuilder;
use S\Http\Response;
use Util\Rand;

class Image extends Abstraction {

    protected $show_type = false; //true: 返回base64内容， false: 直接显示
    protected $content = false; //验证码内容
    protected $id = ''; //验证码id, 同个页面多个验证码时使用

    public function __construct($content = '', $show_type = false, $id = '') {
        $this->content = $content ?: Rand::human();
        $this->show_type = $show_type;
        $this->id = $id;
    }

    public function show() {
        $_SESSION['verify_code' . $this->id] = strtolower($this->content);

        if ($this->show_type) {
            return CaptchaBuilder::create($this->content)->build()->inline();
        }

        Response::setFormatter(Response::FORMAT_PLAIN);
        header('Content-type: image/jpeg');
        CaptchaBuilder::create($this->content)->build(80, 30)->output();

        return true;
    }

    public function verify($code) {
        return $code == $_SESSION['verify_code'] . $this->id;
    }
}