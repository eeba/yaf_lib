<?php

namespace Util;

use Base\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Request
 *
 * @description Http请求服务工具
 */
class Request
{

    const ERROR_CODE = '30040001';
    const ERROR_MESSAGE = 'HTTP ERROR';

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    /**
     * @var Client
     */
    private Client $_client;
    /**
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     */
    private static array $_options_sets = array(
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

    private static array $_method = array(
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
     * @param array $options default array() 请求配置
     *
     * @throws Exception
     */
    public function __construct(string $base_uri, array $options = array())
    {
        if (!$this->checkOptions($options)) {
            throw new Exception('invalid options');
        }

        $config['base_uri'] = $base_uri;
        $config['timeout'] = 10;
        $config['connect_timeout'] = 3;
        $config['version'] = '1.1';
        $config['http_errors'] = true;
        $config['verify'] = false;

        $this->_client = new Client(array_merge($config, $options));

    }

    /**
     * 外部请求
     *
     * @param string $method 请求方法
     * @param string $path 请求资源相对路径 e.g. foo/bar foo/bar?a=1&b=2
     * @param mixed $data default array()
     * @param array $options default array()
     *
     * @return string
     * @throws Exception
     * @throws GuzzleException
     */
    public function request(string $method, string $path, $data = array(), array $options = array()): string
    {
        $method = strtolower($method);

        if (!$this->checkMethod($method)) {
            throw new Exception('invalid http method:' . $method);
        }
        if (!$this->checkOptions($options)) {
            throw new Exception('invalid options');
        }

        if (self::METHOD_POST == $method && $data && is_array($data)) {
            $options['form_params'] = $data;
        } elseif (self::METHOD_GET == $method) {
            $options['query'] = $data;
        } else {
            $options['body'] = $data;
        }

        $headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Language' => 'zh-CN,zh;q=0.9,en;q=0.8,fr;q=0.7,it;q=0.6,ja;q=0.5,zh-TW;q=0.4,nb;q=0.3,es;q=0.2,pt;q=0.1,af;q=0.1,so;q=0.1,pl;q=0.1,de;q=0.1,sm;q=0.1',
            'Cache-Control' => 'max-age=0',
            'User-Agent' => 'Googlebot/2.1 (+http://www.googlebot.com/bot.html)'
            //'User-Agent'=>'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
        ];
        if (isset($options['headers']) && is_array($options['headers']) && !empty($options['headers'])) {
            $options['headers'] = array_merge($headers, $options['headers']);
        } else {
            $options['headers'] = $headers;
        }

        try {
            $response = $this->_client->request($method, $path, $options);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), self::ERROR_CODE, $e);
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
    private function checkMethod(string $method): bool
    {
        return in_array($method, self::$_method);
    }

    /**
     * 校验options选项是否合法
     *
     * @param array $options
     *
     * @return bool
     */
    private function checkOptions(array $options): bool
    {
        if ($options) {
            return empty(array_diff(array_keys($options), self::$_options_sets));
        } else {
            return true;
        }
    }
}