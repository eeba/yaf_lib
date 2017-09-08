<?php
namespace Cache\Handler;

/**
 * Class Abstraction
 *
 * @package S\Cache\Handler
 * @description 缓存基类
 */
abstract class Abstraction implements CacheInterface {

    const DEFAULT_CONNECT_TIMEOUT = 1;
    const DEFAULT_SEND_TIMEOUT = 1;
    const DEFAULT_RECV_TIMEOUT = 1;
    /**
     * 默认缓存时间，单位s
     */
    const DEFAULT_EXPIRE = 60;

    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = array();
    /**
     * 传入的表示信息
     *
     * @var string
     */
    protected $name = "";
    /**
     * 调用的子类
     *
     * @var string
     */
    protected $type = "";

    public function __construct() {
        $this->init();
    }

    /**
     * 定义缓存的configure接口，用来实现缓存的初始化及配置工作
     *
     * @param string $type 缓存类型
     * @param mixed  $name 配置数据
     *
     * @throws \Base\Exception
     */
    public function configure($type, $name = '') {
        $this->type = $type;
        $this->name = $name;
        $this->config = \Base\Config::get('service.cache.' . $type);
        if (!$this->config) {
            throw new \Base\Exception(get_class($this) . ' need be configured. Config : ' . $name);
        }
    }

    /**
     * 定义缓存的批量get接口
     *
     * @param array $keys 包含key的数组
     *
     * @return array|false  只有查询错误才会返回false
     */
    public function mget(array $keys) {
        $ret = array();
        foreach ($keys as $key) {
            $ret[$key] = $this->get($key);
        }

        return $ret;
    }

    /**
     * 定义缓存的批量set接口
     *
     * @param array $values 包含key=>value的数组
     * @param int   $expire 过期时间
     *
     * @return bool
     */
    public function mset(array $values, $expire = 60) {
        $ret = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $expire)) {
                $ret = $this->set($key, $value, $expire);
            }
        }

        return $ret;
    }

    /**
     * 定义缓存的批量del接口
     *
     * @param array $keys 包含key的数组
     *
     * @return bool
     */
    public function mdel(array $keys) {
        $ret = true;
        foreach ($keys as $key) {
            if (!$this->del($key)) {
                $ret = $this->del($key);
            }
        }

        return $ret;
    }

    protected function init() {
    }

    /**
     * 关闭缓存连接
     *
     * 对于某些需要连接的缓存，比如MC,Redis等，手动关闭缓存可以提供更好的性能优化。
     */
    abstract public function close();

    /**
     * 获取缓存的静态实例
     */
    abstract protected function getInstance();
}