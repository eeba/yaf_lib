<?php

namespace Captcha;

abstract class Abstraction
{

    abstract function show();

    abstract function verify($code);

}