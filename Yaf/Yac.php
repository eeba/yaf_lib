<?php


/**
 * @method bool add(string|array $key, mixed $value, int $ttl = 0)
 * @method string|array get(string|array $key, mixed &$cas = NULL)
 * @method bool set(string|array $key, mixed $value, int $ttl = 0)
 * @method bool delete(string|array $key, int $delay = 0)
 * @removed bool flush()
 */
class Yac
{
    protected string $_prefix = "";
}