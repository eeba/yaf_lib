<?php
namespace Base;

class Env {

    private static $controller_name = '';

    public static function isCli(){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->isCli();
    }

    public static function getControllerName(){
        if(self::isCli()){
            return self::$controller_name;
        } else {
            return \Yaf\Application::app()->getDispatcher()->getRequest()->getControllerName();
        }
    }

    public static function setControllerName($controller_name){
        self::$controller_name = str_replace('\\', '/', $controller_name);
    }
}