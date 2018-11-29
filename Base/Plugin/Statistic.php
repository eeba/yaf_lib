<?php
namespace Base\Plugin;

class Statistic extends \Yaf\Plugin_Abstract{

    /**
     * 访问量统计
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $uri = $request->getRequestUri();
        $redis = new \Db\Redis();
        $host = strtoupper($request->getServer('HTTP_HOST'));
        $redis->hIncrBy($host . '_STATISTICS'.date('Ymd'), $uri, 1);
    }

}