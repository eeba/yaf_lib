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
    const DEFAULT_EXPIRE = 300;  //默认缓存时间，单位s

    abstract public function get(string $key): string|array;

    abstract public function set(string $key, bool|string|array $value, int $ttl): bool;

    abstract public function del(string $key): bool;

    abstract protected function getInstance();
}