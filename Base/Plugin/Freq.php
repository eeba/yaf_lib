<?php

namespace Base\Plugin;

use Util\Ip;
use Base\Exception;
use Yaf_Request_Abstract;
use Yaf_Response_Abstract;

class Freq extends \Yaf_Plugin_Abstract
{

    /**
     * 频率限制
     *
     * @param Yaf_Request_Abstract $request
     * @param Yaf_Response_Abstract $response
     * @return void
     * @throws Exception
     */
    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response): void
    {
        $ret = true;
        foreach ($this->freqConfig() as $key => $value) {
            $ret = (new \Security\Freq())->add('ACCESS_TIMES', $key, $value['threshold'], $value['ttl']);
        }
        if (!$ret) {
            throw new Exception('访问速度太快，请稍后再试', 9999999);
        }
    }

    /**
     * return array(
     *             $key => array(   //$key //唯一标示
     *                 'threshold' => '',  //阈值
     *                 'ttl' => ''         //过期时间(秒)
     *             ),
     *       );
     */
    private function freqConfig(): array
    {
        $key = ip2long(Ip::getClientIp());

        return array(
            $key => array(
                'threshold' => 1000,
                'ttl' => 60,
            ),
        );
    }

}
