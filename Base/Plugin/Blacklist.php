<?php
namespace Base\Plugin;

use Util\Ip;
use Base\Exception;

abstract class Blacklist extends \Yaf\Plugin_Abstract{

    /**
     * IP黑名单
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     * @throws Exception
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $ip = Ip::getClientIp();

        $blacklist = $this->blackListConfig();

        if(in_array($ip, $blacklist)){
            throw new Exception('你好的IP暂时禁止访问');
        }

    }

    /**
     * ip黑名单列表
     * @return array
     */
    abstract function blackListConfig();

}
