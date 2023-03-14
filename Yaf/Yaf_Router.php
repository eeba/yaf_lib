<?php

/**
 * @method __construct()
 * @method addConfig(Yaf_Config_Abstract $config): bool
 * @method addRoute(string $name, Yaf_Route_Abstract $route): bool
 * @method getCurrentRoute(): string
 * @method getRoute(string $name): Yaf_Route_Interface
 * @method getRoutes(): mixed
 * @method route(Yaf_Request_Abstract $request): bool
 */
final class Yaf_Router
{
    protected array $_routes;
    protected string $_current;
}