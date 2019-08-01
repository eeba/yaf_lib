<?php
namespace Api;
use Base\Config;
use Base\Logger;
use S\Http\Curl;
use function GuzzleHttp\Psr7\build_query;

class Util{
    protected $host = 'https://api.u7c.cn';
    protected $app_key = 'server.api.u7c.app_key';
    protected $app_secret = 'server.api.u7c.app_secret';

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
            "m"      => $this->getSign($timestamp, $sign_params),
        );

        $uri .= (strpos($uri,'?') ? '&':'?') . build_query($get_params);

        $response = (new Curl($this->host, $option))->request("post", $uri, $params, $upload_file, ['timeout'=>$timeout]);

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

        return $result;
    }


    /**
     * 生成请求时所需的签名串
     *
     * @param string $time 接口对应的key值`
     * @param array  $params  向接口传输的参数
     * @return array 带签名的参数串
     */
    public function getSign($time, array $params = array()){
        $app_key = Config::get($this->app_key);
        $app_secret = Config::get($this->app_secret);
        ksort($params, SORT_STRING);
        $sign = $app_key.$app_secret.$time.implode('', $params);

        return md5($sign);
    }
}