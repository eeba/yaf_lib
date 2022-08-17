<?php

namespace File;

abstract class Abstraction
{
    abstract public function put($local, $target, $type);

    abstract public function get($target, $local);
}