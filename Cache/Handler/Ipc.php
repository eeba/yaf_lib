<?php
namespace Cache\Handler;

/**
 * inner process cache
 * 进程内缓存，全局变量的一种实现
 */
class Ipc implements CacheInterface {

    protected $storage = array();
    protected $size = 0;

    /**
     * 实现缓存的get接口
     *
     * @param string $key key值
     *
     * @return mixed
     */
    public function get($key) {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }

        return false;
    }

    /**
     * 实现缓存的set接口
     *
     * @param string $key   key值
     * @param mixed  $value value值
     * @param int    $max   最大缓存元素数，注意请勿设置过大，除非你确定不会引起内存泄露之类的问题
     *
     * @return bool
     */
    public function set($key, $value, $max = 60) {
        if ($this->size > $max) {
            array_shift($this->storage);
        }
        $this->storage[$key] = $value;
        $this->size++;

        return true;
    }

    /**
     * 实现缓存的del接口
     *
     * @param string $key key值
     *
     * @return bool
     */
    public function del($key) {
        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);

            return true;
        }

        return false;
    }

    /**
     * 实现缓存的mget接口
     *
     * @param array $keys 包含key值的数组
     *
     * @return array
     */
    public function mget(array $keys) {
        $ret = array_fill_keys($keys, false);

        return array_merge($ret, array_intersect_key($this->storage, $ret));
    }

    /**
     * 实现缓存的mset接口
     *
     * @param array $values 包含key=>value的数组
     * @param int   $max
     *
     * @return true
     */
    public function mset(array $values, $max = 60) {
        $keys = array_intersect_key($this->storage, $values);
        $size = count($keys);
        if ($size + $this->size > $max) {
            $unset = 0;
            $count = $size + $this->size - $max;
            foreach ($this->storage as $key => $v) {
                if (!in_array($key, $keys)) {
                    unset($this->storage[$key]);
                    if ($unset++ >= $count) {
                        break;
                    }
                }
            }
        }
        $this->storage = array_merge($this->storage, $values);

        return true;
    }

    /**
     * 实现缓存的mdel接口
     *
     * @param array $keys 包含key的数组
     *
     * @return true
     */
    public function mdel(array $keys) {
        $cached = array_intersect(array_keys($this->storage), $keys);
        foreach ($cached as $key) {
            unset($this->storage[$key]);
        }

        return true;
    }

}