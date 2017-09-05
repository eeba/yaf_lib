<?php
namespace Msg\Handler;

abstract class Abstraction {
    public $config;

    public function __construct($config = '') {

    }

    abstract function send($to, $title, $msg, $files = []);
}