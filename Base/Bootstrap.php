<?php
namespace Base;

class Bootstrap extends \Yaf\Bootstrap_Abstract {

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
    public function changeRoute(\Yaf\Dispatcher $dispatcher, $ori = '', $new = '') {
        $request_uri = $dispatcher->getRequest()->getRequestUri();
        $new_request_uri = str_ireplace('//','/',str_ireplace($ori, $new, $request_uri));
        $yaf_request = new \Yaf\Request\Http($new_request_uri);
        $dispatcher->setRequest($yaf_request);
    }
}