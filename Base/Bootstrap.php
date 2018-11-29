<?php
namespace Base;

class Bootstrap extends \Yaf\Bootstrap_Abstract {
    public function _initDebug() {
        if (defined(DEBUG) && DEBUG) {
            error_reporting(E_ALL);
            ini_set('display_errors', true);
        }
    }

    /**
     * 在此处注册非YAF的autoload
     */
    public function _initBaseLoader() {
        $loader = include(LIB . '/vendor/autoload.php');
        $loader->addPsr4('Service\\', ROOT_PATH . '/library/Service');
        $loader->addPsr4('Data\\', ROOT_PATH . '/library/Data');
        $loader->addPsr4('Dao\\', ROOT_PATH . '/library/Dao');
        $loader->addPsr4('Job\\', ROOT_PATH . '/job');
    }

    /**
     * 初始化环境
     */
    public function _initEnv() {
        \Base\Env::init();
    }
}