<?php
namespace Base;

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initCheckDefined(){
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
            'DEBUG'
        );
        foreach ($define_list as $define){
            if(!defined($define)){
                exit("没有定义常量`{$define}`");
            }
        }
    }

    /**
     * 请求响应的格式
     */
    public function _initResponseFormatter(){
        if((new \Yaf\Request\Http())->isXmlHttpRequest()){
            \S\Http\Response::setFormatter(\S\Http\Response::FORMAT_JSON);
        }else{
            \S\Http\Response::setFormatter(\S\Http\Response::FORMAT_HTML);
        }
    }

    /**
     * 三方类库
     *
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initVendor(\Yaf\Dispatcher $dispatcher){
        $loader = include(LIB_PATH . '/vendor/autoload.php');
        $loader->addPsr4('Script\\', APP_PATH . '/script');

    }

    /**
     * 注册本地命名空间
     *
     * @param \Yaf\Dispatcher $dispatcher
     */
    public function _initRegisterLocalNameSpace(\Yaf\Dispatcher $dispatcher) {
        \Yaf\Loader::getInstance()->registerLocalNamespace('Service');
        \Yaf\Loader::getInstance()->registerLocalNamespace('Data');
        \Yaf\Loader::getInstance()->registerLocalNamespace('Dao');
    }


    /**
     * 默认web模块， 其它模块用uri区分时启用
     * @param \Yaf\Dispatcher $dispatcher
     * @param string $ori
     * @param string $new
     */
    public function _initDebug(\Yaf\Dispatcher $dispatcher) {
        if(DEBUG) {
            ini_set('display_errors', true);
            error_reporting(E_ALL);
        }else{
            error_reporting(0);
        }
    }
}