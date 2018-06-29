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
    }

    /**
     * 初始化环境
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initEnv(\Yaf\Dispatcher $dispatcher) {
        \Base\Env::init($dispatcher->getRequest());
    }
}