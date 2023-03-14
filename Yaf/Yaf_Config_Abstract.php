<?php
/**
 * Yaf自动补全类(基于最新的3.3.3版本)
 * @author shixinke(http://www.shixinke.com)
 * @modified 2021/12/01
 */

/**
 * @method readonly(): bool
 * @method toArray(): array
 * @method __get (string $name):mixed
 * @method __set(string $name, mixed $value): void
 * @method __isset(string $name): void
 * @method key(): void
 * @method next(): void
 * @method offsetExists($name): void
 * @method offsetGet($name): void
 * @method offsetSet($name, $value): void
 * @method offsetUnset($name): void
 * @method rewind(): void
 * @method valid(): void
 * @method count(): void
 * @method current(): void
 */
abstract class Yaf_Config_Abstract implements Iterator, ArrayAccess, Countable
{
    protected array $_config;
    protected string $_readonly = "1";
}

