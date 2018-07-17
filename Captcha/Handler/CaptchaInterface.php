<?php
namespace Captcha\Handler;

interface CaptchaInterface {
    /**
     * 验证码展示
     * @param $args
     * @return mixed
     */
    public function show($args);
}
