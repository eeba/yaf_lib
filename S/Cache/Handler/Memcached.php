<?php
namespace S\Cache\Handler;

/**
 * 缓存的memcached方式实现
 *
 * @method bool setOption
 */
class Memcached extends Abstraction {
    /**
     * @var \Memcached $mc
     */
    protected $mc = null;

    /**
     * 实现缓存的get接口
     *
     * @param string $key key值
     * @return mixed
     */
    public function get($key) {
        $ret = $this->getInstance()->get($key);
        return $ret;
    }

    /**
     * 实现缓存的set接口
     *
     * @param string $key key值
     * @param mixed  $value value值
     * @param int    $expire 过期时间
     * @return bool
     */
    public function set($key, $value, $expire = 60) {
        $ret = $this->getInstance()->set($key, $value, $expire);
        return $ret;
    }

    /**
     * 实现缓存的del接口
     *
     * @param string $key key值
     * @return bool
     */
    public function del($key) {
        $ret = $this->getInstance()->delete($key);
        return $ret;
    }

    /**
     * 实现缓存的mget接口
     *
     * @param array $keys 包含key值的数组
     * @return array
     */
    public function mget(array $keys) {
        $ret = $this->getInstance()->getMulti($keys);
        return $ret;
    }

    /**
     * 实现缓存的mset接口
     *
     * @param array $values 包含key=>value的数组
     * @param int   $expire 过期时间
     * @return bool
     */
    public function mset(array $values, $expire = 60) {
        $ret = $this->getInstance()->setMulti($values, $expire);
        return $ret;
    }

    /**
     * @param     $key
     * @param int $value
     * @return int|false
     */
    public function increment($key, $value = 1) {
        $ret = $this->getInstance()->increment($key, $value);
        return $ret;
    }

    /**
     * @param     $key
     * @param int $value
     * @return int|false
     */
    public function decrement($key, $value = 1) {
        $ret = $this->getInstance()->decrement($key, $value);
        return $ret;
    }

    /**
     * 去掉危险操作的功能
     * @return bool
     */
    public function flush() {
        return false;
    }

    public function close() {
        $this->getInstance()->quit();
        $this->mc = null;
        return true;
    }

    /**
     * 将其他方法调用均转向到封装的Memcached实例
     *
     * @param string $name
     * @param array  $args
     * @return mixed
     */
    public function __call($name, $args = array()) {
        $ret = call_user_func_array(array($this->getInstance(), $name), $args);
        return $ret;
    }

    public function __destruct() {
        $this->close();
    }

    protected function getInstance() {
        if (!$this->mc) {
            $this->mc = new \Memcached();
            $this->addServer();
            $this->setOptions();
        }
        return $this->mc;
    }

    /**
     * 初始化配置信息，以addServers方式使用
     */
    protected function addServer() {
        if (is_array(current($this->config))) {
            $this->mc->addServers($this->config);
        } else {
            $this->mc->addServer($this->config['host'], $this->config['port'], $this->config['weight'] ?: 0);
        }
    }

    /**
     * 配置信息
     * 配置项参考 http://cn2.php.net/manual/zh/memcached.constants.php
     * 默认设置几项超时时间为1000ms
     * @param array $options
     */
    protected function setOptions(array $options = array()) {
        !isset($options[\Memcached::OPT_CONNECT_TIMEOUT]) && $options[\Memcached::OPT_CONNECT_TIMEOUT] = self::DEFAULT_CONNECT_TIMEOUT * 1000;
        !isset($options[\Memcached::OPT_SEND_TIMEOUT]) && $options[\Memcached::OPT_SEND_TIMEOUT] = self::DEFAULT_SEND_TIMEOUT * 1000;
        !isset($options[\Memcached::OPT_RECV_TIMEOUT]) && $options[\Memcached::OPT_RECV_TIMEOUT] = self::DEFAULT_RECV_TIMEOUT * 1000;
        $options[\Memcached::OPT_COMPRESSION] = false;
        $options[\Memcached::OPT_BINARY_PROTOCOL] = true;
        foreach ($options as $key => $v) {
            $this->mc->setOption($key, $v);
        }

        if (isset($this->config['username']) && isset($this->config['password'])) {
            //设置OCS帐号密码进行鉴权,如已开启免密码功能，则无需此步骤
            $this->mc->setSaslAuthData($this->config['username'], $this->config['password']);
        }
    }

}
