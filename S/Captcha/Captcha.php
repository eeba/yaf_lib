<?php
namespace S\Captcha;

/**
 * Class Captcha
 *
 * @method show()
 *
 * @package S\Captcha
 */
class Captcha {
    const TYPE_IMAGE = 'image';

    private static $instance = null;

    public static function getInstance($type = self::TYPE_IMAGE) {
        if (self::$instance === null) {
            $handler = __NAMESPACE__ . "\\Handler\\" . ucfirst($type);
            self::$instance = new $handler();
        }
        return self::$instance;
    }
}