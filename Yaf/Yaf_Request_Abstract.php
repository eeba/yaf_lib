<?php

/**
 * @method clearParams(): bool
 * @method getActionName(): string
 * @method getBaseUri(): string
 * @method getControllerName(): string
 * @method getEnv(string $name, string $default): string
 * @method getException(): void
 * @method getLanguage(): string
 * @method getMethod(): string
 * @method getModuleName(): string
 * @method getParam(string $name, string $default): string
 * @method getParams(): array
 * @method getRequestUri(): string
 * @method getServer(string $name, string $default = ""): string
 * @method isCli(): bool
 * @method isDispatched(): bool
 * @method isGet(): bool
 * @method isHead(): bool
 * @method isOptions(): bool
 * @method isPost(): bool
 * @method isPut(): bool
 * @method isRouted(): bool
 * @method isXmlHttpRequest(): bool
 * @method setActionName(string $action, bool $format_name = true): void
 * @method setBaseUri(string $uir): bool
 * @method setControllerName(string $controller, bool $format_name = true): void
 * @method setDispatched(): void
 * @method setModuleName(string $module, bool $format_name = true): void
 * @method setParam(string $name, string $value): bool
 * @method setRequestUri(string $uir): void
 * @method setRouted(string $flag): void
 */
abstract class Yaf_Request_Abstract
{
    const SCHEME_HTTP = 'http';
    const SCHEME_HTTPS = 'https';

    public string $module;
    public string $controller;
    public string $action;
    public string $method;
    protected array $params;
    protected string $language;
    protected Exception $_exception;
    protected string $_base_uri;
    protected string $uri;
    protected bool $dispatched;
    protected bool $routed;
}

