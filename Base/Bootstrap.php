<?php
namespace Base;

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    /**
     * 在此处注册非YAF的autoload
     */
    public function _initBaseLoader() {
        $loader = include(LIB . '/vendor/autoload.php');
        $loader->addPsr4('Service\\', APP_PATH . '/library/Service');
        $loader->addPsr4('Data\\', APP_PATH . '/library/Data');
        $loader->addPsr4('Dao\\', APP_PATH . '/library/Dao');
        $loader->addPsr4('Job\\', APP_PATH . '/job');
    }

    /**
     * 初始化环境
     */
    public function _initEnv() {
        \Base\Env::init();
    }

    public function _initDebug(){
        if(DEBUG) {
            error_reporting(E_ALL);
            ini_set('display_errors', true);
        }
    }
}