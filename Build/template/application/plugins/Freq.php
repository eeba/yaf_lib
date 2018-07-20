<?php

class Plugin_Freq extends \Yaf\Plugin_Abstract{

    //频率限制
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $uri = $request->getModuleName() . '_' .$request->getControllerName() . '_' .$request->getActionName();
        $key = $uri . '_' . ip2long(Util\Ip::getClientIp());
        $ret = (new Security\Freq())->add('ACCESS_TIMES', $key, 25, 60);
        if (!$ret) {
            throw new Exception('访问速度太快，请稍后再试', 9999999);
        }
    }

}
