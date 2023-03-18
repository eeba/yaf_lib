<?php

namespace Base\Plugin;

use Util\Ip;
use Base\Exception;
use Yaf_Request_Abstract;
use Yaf_Response_Abstract;

class Blacklist extends \Yaf_Plugin_Abstract
{

    /**
     * IP黑名单
     *
     * @param Yaf_Request_Abstract $request
     * @param Yaf_Response_Abstract $response
     * @return void
     * @throws Exception
     */
    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response): void
    {
        $ip = Ip::getClientIp();

        $blacklist = $this->blackListConfig();

        if (in_array($ip, $blacklist)) {
            throw new Exception('因恶意操作，暂时禁止访问');
        }

    }

    /**
     * ip黑名单列表
     * @return array
     */
    private function blackListConfig(): array
    {

        return [];
    }

}
