<?php
namespace Base\Plugin;

use Util\Ip;
use Base\Exception;

class Blacklist extends \Yaf_Plugin_Abstract{

    /**
     * IP黑名单
     *
     * @param \Yaf_Request_Abstract $request
     * @param \Yaf_Response_Abstract $response
     * @return bool|void
     * @throws Exception
     */
    public function dispatchLoopStartup(\Yaf_Request_Abstract $request, \Yaf_Response_Abstract $response) {
        $ip = Ip::getClientIp();

        $blacklist = $this->blackListConfig();

        if(in_array($ip, $blacklist)){
            throw new Exception('因恶意操作，暂时禁止访问');
        }

    }

    /**
     * ip黑名单列表
     * @return array
     */
    private function blackListConfig(){

        return [];
    }

}
