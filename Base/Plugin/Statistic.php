<?php
namespace Base\Plugin;

use Base\Env;
use Util\Ip;

class Statistic extends \Yaf\Plugin_Abstract{

    /**
     * 访问量统计
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        if(Env::isCli()) {
            return ;
        }

        $uri = $request->getRequestUri();
        $ip = Ip::getClientIp();
        $redis = new \S\Data\Redis();
        $redis->hIncrBy(APP . '_STATISTICS_URI_'.date('Ymd'), $uri, 1);
        $redis->hIncrBy(APP . '_STATISTICS_IP_'.date('Ymd'), $ip, 1);
    }

}