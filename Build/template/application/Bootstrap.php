<?php

class Bootstrap extends Yaf\Bootstrap_Abstract {

    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
        $dispatcher->registerPlugin(new Plugin_Session());
    }

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