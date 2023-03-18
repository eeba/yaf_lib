<?php

namespace Msg;

abstract class Abstraction
{
    protected array $config;

    public function __construct($config = '')
    {

    }

    abstract function send($to, $title, $msg, $files = []);
}