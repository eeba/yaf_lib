<?php
namespace Base\Util;

class OS {

    public static function is32bitSystem() {
        return PHP_INT_MAX > 2147483647 ? true : false;
    }
}