<?php

namespace Base;

class Bootstrap extends \Yaf_Bootstrap_Abstract
{

    public function _initEnv()
    {
        require CONF_PATH . "/env.php";
    }

    public function _initCheckDefined()
    {
        $define_list = array(
            'ROOT_PATH',
            'CONF_PATH',
            'LIB_PATH',
            'LOG_PATH',
            'APP',
            'APP_NAME',
            'APP_PATH',
            'APP_HOST',
            'APP_ADMIN_HOST',
            'APP_STATIC_HOST',
            'SESSION_TYPE',
        );
        foreach ($define_list as $define) {
            if (!defined($define)) {
                exit("没有定义常量`{$define}`");
            }
        }
    }

    /**
     * 三方类库
     *
     * @param \Yaf_Dispatcher $dispatcher
     */
    public function _initVendor(\Yaf_Dispatcher $dispatcher)
    {
        $loader = include(LIB_PATH . '/vendor/autoload.php');
        $loader->addPsr4('Script\\', APP_PATH . '/script');

    }

    /**
     * 注册本地命名空间
     *
     * @param \Yaf_Dispatcher $dispatcher
     */
    public function _initRegisterLocalNameSpace(\Yaf_Dispatcher $dispatcher)
    {
        \Yaf_Loader::getInstance()->registerLocalNamespace('Service');
        \Yaf_Loader::getInstance()->registerLocalNamespace('Data');
        \Yaf_Loader::getInstance()->registerLocalNamespace('Dao');
    }
}