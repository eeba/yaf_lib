<?php
namespace Base\Plugin;

use Util\Ip;
use Base\Exception;

abstract class Freq extends \Yaf\Plugin_Abstract{

    /**
     * 频率限制
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return bool|void
     * @throws Exception
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
        foreach ($this->freqConfig() as $key => $value) {
            $ret = (new \S\Security\Freq())->add('ACCESS_TIMES', $key, $value['threshold'], $value['ttl']);
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
    abstract function freqConfig();

}
