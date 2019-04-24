<?php
namespace S\Db;

use Base\Exception;
use Base\Config;

/**
 * Class Redis
 * @method get( $key )
 * @method set( $key, $value, $timeout = 0 )
 * @method setex( $key, $ttl, $value )
 * @method setnx( $key, $value )
 * @method del( $key1, $key2 = null, $key3 = null )
 * @method delete( $key1, $key2 = null, $key3 = null )
 * @method incr( $key )
 * @method incrBy( $key, $value )
 * @method decr( $key )
 * @method decrBy( $key, $value )
 * @method lPush( $key, $value1, $value2 = null, $valueN = null )
 * @method rPush( $key, $value1, $value2 = null, $valueN = null )
 * @method lPop( $key )
 * @method rPop( $key )
 * @method lLen( $key )
 * @method hSet( $key, $hashKey, $value )
 * @method hSetNx( $key, $hashKey, $value )
 * @method hGet($key, $hashKey)
 * @method hDel( $key, $hashKey1, $hashKey2 = null, $hashKeyN = null )
 * @method hGetAll( $key )
 * @method hIncrBy( $key, $hashKey, $value )
 * @package Db
 */
class Redis {

    const DEFAULT_CONNECT_TIMEOUT = 5;
    const NAME_DEFAULT = 'common';

    protected static $res = array();
    protected $config = array();
    protected $name = '';

    /**
     * 用于判断是否使用持久连接，如果使用，析构函数中不做close操作
     *
     * @var bool
     */
    private $_persistent = false;

    /**
     * @param string $name
     */
    public function __construct($name = self::NAME_DEFAULT) {
        $this->name = $name;
    }

    /**
     * @param $name
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $args = array()) {
        try {
            $ret = call_user_func_array(array($this->getInstance(), $name), $args);
        } catch (\Exception $e) {
            //发生异常清除会话handle
            $this->close();
            //异常处理
            if ($e instanceof Exception) {
                throw $e;
            } else {
                throw new Exception($e->getCode() . ' ' . $e->getMessage());
            }
        }

        return $ret;
    }

    /**
     * 去掉危险操作的功能
     *
     * @return bool
     */
    public function flush() {
        return false;
    }

    public function close() {
        (self::$res[$this->name] && isset(self::$res[$this->name]->socket)) && self::$res[$this->name]->close();
        self::$res[$this->name] = null;

        return true;
    }

    public function __destruct() {
        if (!$this->_persistent) {
            $this->close();
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getInstance() {
        if (!isset(self::$res[$this->name])) {
            self::$res[$this->name] = new \Redis();
            $this->configure();
            $this->connect();
            $this->setOptions();
        }
        return self::$res[$this->name];
    }

    /**
     * 初始化配置信息，以addServers方式使用
     * @throws Exception
     */
    protected function configure() {
        $this->config = Config::get('server.redis.' . $this->name);
        if (!$this->config) {
            throw new Exception(get_class($this) . ' need be configured. Config : ' . $this->name);
        }
    }

    /**
     * 链接redis
     * @return bool
     * @throws Exception
     */
    protected function connect() {
        if (isset($this->config['persistent']) && $this->config['persistent']) {
            $this->_persistent = true;
            $conn = self::$res[$this->name]->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if (false === $conn) {
                $conn = self::$res[$this->name]->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        } else {
            $conn = self::$res[$this->name]->connect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            //重连一次
            if (false === $conn) {
                $conn = self::$res[$this->name]->connect($this->config['host'], $this->config['port'], $this->config['timeout'] ?: self::DEFAULT_CONNECT_TIMEOUT);
            }
        }

        if (false === $conn) {
            throw new Exception("redis connect " . $this->config['host'] . " fail");
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected function setOptions() {
        if (isset($this->config['user']) && $this->config['user'] && $this->config['auth']) {
            if (self::$res[$this->name]->auth($this->config['user'] . ":" . $this->config['auth']) == false) {
                throw new Exception("redis auth " . $this->config['host'] . " fail");
            }
        }
        if (isset($this->config['db']) && $this->config['db']) {
            self::$res[$this->name]->select($this->config['db']);
        }
    }
}