<?php
namespace Base;

class TestCase extends \PHPUnit_Framework_TestCase {
    protected static $_app;
    protected static $_http;

    public function setUp(){
        $application = \Yaf\Registry::get('application');
        if (!$application) {
            self::$_app = (new \Yaf\Application(ROOT_PATH . '/conf/application.ini'))->bootstrap();
            \Yaf\Registry::set('application', self::$_app);
        }else{
            self::$_app = $application;
        }
    }

    protected function request(){
        if(!self::$_http){
            self::$_http = new \Http\Curl(HOST);
        }
        return self::$_http;
    }
}