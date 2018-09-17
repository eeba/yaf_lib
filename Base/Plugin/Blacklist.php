<?php
namespace Base\Plugin;

use Util\Ip;
use Base\Exception;

class Blacklist extends \Yaf\Plugin_Abstract{

    //IP黑名单
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        $ip = Ip::getClientIp();

        $blacklist = [];

        if(in_array($ip, $blacklist)){
            throw new Exception('因恶意操作，暂时禁止访问');
        }

    }

}
