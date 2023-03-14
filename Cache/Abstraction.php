<?php

namespace Cache;


/**
 * Class Abstraction
 *
 * @package Cache\Handler
 * @description 缓存抽象类
 */
abstract class Abstraction
{
    const DEFAULT_EXPIRE = 60;  //默认缓存时间，单位s
    protected $config = [];     //配置信息
    protected $name = "";       //缓存配置项
    protected $type = "";       //缓存类型

    /**
     * 定义缓存的批量get接口
     *
     * @param array $keys 包含key的数组
     *
     * @return array|false  只有查询错误才会返回false
     */
    public function mget(array $keys)
    {
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
     * @param int $expire 过期时间
     *
     * @return bool
     */
    public function mset(array $values, $expire = 60)
    {
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
    public function mdel(array $keys)
    {
        $ret = true;
        foreach ($keys as $key) {
            if (!$this->del($key)) {
                $ret = $this->del($key);
            }
        }

        return $ret;
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