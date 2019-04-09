<?php
namespace S\Cache\Handler;

/**
 * Interface CacheInterface
 *
 * @package Cache\Handler
 * @description 缓存操作接口定义
 */
interface CacheInterface {
    /**
     * 定义缓存的get接口
     *
     * @param string $key key值
     *
     * @return mixed
     */
    public function get($key);

    /**
     * 定义缓存的set接口
     *
     * @param string $key key值
     * @param mixed  $value value值
     * @param int    $expire 过期时间
     *
     * @return bool
     */
    public function set($key, $value, $expire = 60);

    /**
     * 定义缓存的del接口
     *
     * @param string $key key值
     *
     * @return bool
     */
    public function del($key);
}