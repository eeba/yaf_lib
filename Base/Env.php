<?php

namespace Base;

class Env
{

    private static string $controller_name = '';

    public static function isCli()
    {
        return \Yaf_Application::app()->getDispatcher()->getRequest()->isCli();
    }

    public static function getControllerName(): string
    {
        if (self::isCli()) {
            return self::$controller_name;
        } else {
            return \Yaf_Application::app()->getDispatcher()->getRequest()->getControllerName();
        }
    }

    public static function setControllerName($controller_name): void
    {
        self::$controller_name = str_replace('\\', '/', $controller_name);
    }

    public static function isProduct(): bool
    {
        $environ = \Yaf_Application::app()->environ();

        return strtolower($environ) == 'product';
    }

    public static function getRequestUri()
    {
        return \Yaf_Application::app()->getDispatcher()->getRequest()->getRequestUri();
    }

}