<?php
namespace S\Http;

use Base\Exception;

/**
 * Class Curl
 *
 * @description Http请求服务工具
 */
class Curl {

    const ERROR_CODE = '30040001';
    const ERROR_MESSAGE = 'HTTP ERROR';

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    /**
     * @var string 请求资源根路径
     */
    private $_base_uri;
    /**
     * @var \GuzzleHttp\Client
     */
    private $_client;
    /**
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     */
    private static $_options_sets = array(
        'allow_redirects',
        'auth',
        'cert',
        'cookies',
        'connect_timeout',
        'debug',
        'decode_content',
        'delay',
        'expect',
        'form_params',
        'headers',
        'http_errors',
        'json',
        'multipart',
        'proxy',
        'ssl_key',
        'timeout',
    );

    private static $_method = array(
        'get',
        'post',
        'delete',
        'head',
        'put',
        'patch',
    );

    /**
     * Http constructor.
     *
     * @param string $base_uri 服务根路径 e.g. http://demo.com/api
     * @param array  $options default array() 请求配置
     *
     * @throws Exception
     */
    public function __construct($base_uri, array $options = array()) {
        if (!$this->checkOptions($options)) {
            throw new Exception('invalid options');
        }

        $this->_base_uri = $base_uri;

        $config['base_uri'] = $base_uri;
        $config['timeout'] = 10;
        $config['connect_timeout'] = 3;
        $config['version'] = '1.1';
        $config['http_errors'] = true;
        $config['verify'] = false;

        $this->_client = new \GuzzleHttp\Client(array_merge($config, $options));

    }

    /**
     * 外部请求
     *
     * @param string $method 请求方法
     * @param string $path 请求资源相对路径 e.g. foo/bar foo/bar?a=1&b=2
     * @param mixed  $data default array()
     * @param mixed  $upload_file default false
     * @param array  $options default array()
     *
     * @return string
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $path, $data = array(), $upload_file = false, array $options = array()) {
        $method = strtolower($method);

        if (!$this->checkMethod($method)) {
            throw new Exception('invalid http method:' . $method);
        }
        if (!$this->checkOptions($options)) {
            throw new Exception('invalid options');
        }

        if($upload_file){
            $options['multipart'] = $data; //上传文件
        }else{
            if (self::METHOD_POST == $method && $data && is_array($data)) {
                $options['form_params'] = $data;
            } elseif (self::METHOD_GET == $method) {
                $options['query'] = $data;
            }
        }

        $headers = [
            'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding'=>'gzip, deflate',
            'Accept-Language'=>'zh-CN,zh;q=0.9,en;q=0.8,fr;q=0.7,it;q=0.6,ja;q=0.5,zh-TW;q=0.4,nb;q=0.3,es;q=0.2,pt;q=0.1,af;q=0.1,so;q=0.1,pl;q=0.1,de;q=0.1,sm;q=0.1',
            'Cache-Control'=>'max-age=0',
            'User-Agent'=>'Googlebot/2.1 (+http://www.googlebot.com/bot.html)'
            //'User-Agent'=>'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
        ];
        if(isset($options['headers']) && is_array($options['headers']) && !empty($options['headers'])){
            $options['headers'] = array_merge($headers, $options['headers']);
        }else{
            $options['headers'] = $headers;
        }

        try {
            $response = $this->_client->request($method, $path, $options);
        } catch (\Exception $e) {
            throw new Exception(self::ERROR_MESSAGE, self::ERROR_CODE, $e);
        }

        return $response->getBody()->getContents();
    }

    /**
     * 校验http方法是否合法
     *
     * @param string $method
     *
     * @return bool
     */
    private function checkMethod($method) {
        return in_array($method, self::$_method);
    }

    /**
     * 校验options选项是否合法
     *
     * @param array $options
     *
     * @return bool
     */
    private function checkOptions(array $options) {
        if ($options) {
            return empty(array_diff(array_keys($options), self::$_options_sets));
        } else {
            return true;
        }
    }
}