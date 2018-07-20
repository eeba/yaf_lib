<?php

class Plugin_Statistic extends \Yaf\Plugin_Abstract{

    //访问量统计
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $uri = $request->getModuleName() . '/' .$request->getControllerName() . '/' .$request->getActionName();
        $redis = new \Db\Redis();
        $redis->hIncrBy('STATISITC'.date('Ymd'), $uri, 1);
    }

}