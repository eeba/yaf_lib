<?php

namespace Cache;

use Base\Exception;

/**
 * Class \Cache\Redis
 */
class Redis extends Abstraction
{
    protected ?\Base\Dao\Redis $instance = null;
    private string $config_name;

    public function __construct($config_name = "server.redis.cache"){
        $this->config_name = $config_name;
    }


    public function get(string $key): string|array
    {
        return $this->getInstance()->get($key);
    }

    public function set(string $key, bool|array|string $value, int $ttl = self::DEFAULT_EXPIRE): bool
    {
        return $this->getInstance()->set($key, $value, $ttl);
    }

    public function del(string $key): bool
    {
        return $this->getInstance()->delete($key);
    }

    protected function getInstance(): \Base\Dao\Redis
    {
        if (!$this->instance) {
            $this->instance = new \Base\Dao\Redis($this->config_name);
        }

        return $this->instance;
    }
}