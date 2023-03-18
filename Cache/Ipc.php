<?php

namespace Cache;

/**
 * inner process cache
 * 进程内缓存，全局变量的一种实现
 */
class Ipc extends Abstraction
{

    public function get(string $key): string|array
    {
        return (new \Yaf_Registry())->get($key);
    }

    public function set(string $key, bool|array|string $value, int $ttl): bool
    {
        return (new \Yaf_Registry())->set($key, $value);
    }

    public function del(string $key): bool
    {
        return (new \Yaf_Registry())->del($key);
    }

    protected function getInstance()
    {

    }
}