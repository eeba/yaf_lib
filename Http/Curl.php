<?php
namespace Http;

use Base\Exception;
use Base\Config;

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
     * @param array  $options default array()
     *
     * @return string
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $path, $data = array(), array $options = array()) {
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
        try {
            $response = $this->_client->request($method, $path, $options);
        } catch (Exception $e) {
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