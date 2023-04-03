<?php

namespace Cache;

use Base\Exception;

/**
 * Class \Base\Cache\Yac
 *
 *
 * Yac is lockless, that means, there could be a chance you will get a wrong data
 * (depends on how many key slots are allocated and how many keys are stored),
 * so you'd better make sure that your product is not very sensitive to that.
 * According my test(I used the this for test script https://github.com/laruence/yac/blob/master/tests/yac_conflict.php),
 * there is 1/10000000 chance you will get a wrong data, but in the real application, this chance must be less.
 *
 */
class Yac extends Abstraction
{
    protected ?\Yac $instance = null;

    public function get(string $key): string|array
    {
        return $this->getInstance()->get($this->hashKey($key));
    }

    public function set(string $key, bool|array|string $value, int $ttl = self::DEFAULT_EXPIRE): bool
    {
        return $this->getInstance()->set($this->hashKey($key), $value, $ttl);
    }

    public function del(string $key): bool
    {
        return $this->getInstance()->delete($this->hashKey($key));
    }

    protected function getInstance(): \Yac
    {
        if (!$this->instance) {
            $this->instance = new \Yac();
        }

        return $this->instance;
    }


    /**
     * 去掉危险操作的功能
     *
     * @return bool
     */
    public function flush(): bool
    {
        return false;
    }

    public function close(): bool
    {
        $this->instance = null;
        return true;
    }

    public function __destruct()
    {
        $this->close();
    }


    /**
     * 防止超出长度,yac默认限制 48位
     * @param string $key
     * @return string
     */
    private function hashKey(string $key): string
    {
        return md5($key);
    }

}