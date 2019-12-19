<?php
namespace Api;
use Base\Config;
use Base\Logger;
use S\Http\Curl;
use function GuzzleHttp\Psr7\build_query;

class Util{
    private $host = 'https://mp.u7c.cn';
    private $app_key = 'server.mp.u7c.app_key';
    private $app_secret = 'server.mp.u7c.app_secret';

    /**
     * @param       $uri
     * @param array $params
     * @param array $option
     * @param bool $upload_file
     * @param int   $timeout
     *
     * @return string
     * @throws \Base\Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($uri, array $params = array(), $option = array(), $upload_file=false, $timeout = 60){
        $timestamp  = time();

        $sign_params = $upload_file?[]:$params;
        $get_params = array(
            "app_key" => Config::get($this->app_key),
            "t"      => $timestamp,
            "m"      => \S\Security\Sign::getSign(Config::get($this->app_key), Config::get($this->app_secret), $timestamp, $sign_params),
        );

        $uri .= (strpos($uri,'?') ? '&':'?') . build_query($get_params);

        $response = (new Curl($this->host, $option))->request("post", $uri, $params, $upload_file, ['timeout'=>$timeout]);
        Logger::getInstance()->debug(['response' => $response]);

        $result = json_decode($response, true);
        if(!$result){
            Logger::getInstance()->error([$result]);
            throw new \Base\Exception("error format response from api", 5001001);
        }

        if($result['code'] != 2000000){
            Logger::getInstance()->error([$result]);
            throw new \Base\Exception($result["msg"], $result["code"]);
        }

        unset($result["code"]);
        unset($result["msg"]);

        return $result['data'];
    }
}