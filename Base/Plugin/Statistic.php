<?php

namespace Base\Plugin;

use Base\Env;
use Util\Ip;

class Statistic extends \Yaf_Plugin_Abstract
{

    /**
     * 访问量统计
     *
     * @param \Yaf_Request_Abstract $request
     * @param \Yaf_Response_Abstract $response
     * @return bool|void
     */
    public function dispatchLoopStartup(\Yaf_Request_Abstract $request, \Yaf_Response_Abstract $response)
    {
        if (Env::isCli()) {
            return;
        }

        $uri = $request->getRequestUri();
        $ip = Ip::getClientIp();
        $redis = new \Base\Dao\Redis('server.redis.statistic');
        $redis->hIncrBy(APP . '_STATISTICS_URI_' . date('Ymd'), $uri, 1);
        $redis->hIncrBy(APP . '_STATISTICS_IP_' . date('Ymd'), $ip, 1);
    }

}