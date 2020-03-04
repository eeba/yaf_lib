<?php
namespace Api;
use Base\Config;
use Base\Logger;
use S\Http\Curl;
use function GuzzleHttp\Psr7\build_query;

class Util{
    const TIME_OUT = 10;
    const HOST = 'server.api.mp.app_host';
    const APP_KEY = 'server.api.mp.app_key';
    const APP_SECRET = 'server.api.mp.app_secret';

    private $app_host;
    private $app_key;
    private $app_secret;

    public function __construct() {
        $this->app_host = Config::get(self::HOST);
        $this->app_key = Config::get(self::APP_KEY);
        $this->app_secret = Config::get(self::APP_SECRET);
    }

    /**
     * @param       $method
     * @param       $uri
     * @param array|null $params
     * @param array $option
     * @param bool $upload_file
     * @param int   $timeout
     *
     * @return string
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri, array $params = null, $option = array(), $timeout = 60){
        $timestamp  = time();

        $sign_params = array(
            "key"    =>$this->app_key,
            "t"      => $timestamp,
            "m"      => $this->getSign($timestamp, $params),
        );

        $option['timeout'] =  $timeout;

        $uri .= (strpos($uri, '?') === false ? '?' : '&') . http_build_query($sign_params);
        Logger::getInstance()->debug(['host' => $this->app_host, 'uri' => $uri, 'params' => $params, 'option' => $option]);
        $response = (new Curl($this->app_host, $option))->request($method, $uri, $params, $option);
        Logger::getInstance()->debug(['response' => $response]);

        $result = json_decode($response, true);
        if($result){
            if($result['code'] != 2000000){
                Logger::getInstance()->error([$result]);
                throw new \Base\Exception($result["msg"], $result["code"]);
            }

            return $result['data'];
        }else{
            return $response === false ? null : $response;
        }
    }

    public function getSign($timestamp, $sign_params){
        return \S\Security\Sign::getSign($this->app_key, $this->app_secret, $timestamp, $sign_params);
    }
}