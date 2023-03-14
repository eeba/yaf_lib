<?php

/**
 * @method __construct(mixed $config, string $environ = null)
 * @method static app(): mixed
 * @method bootstrap(Yaf_Bootstrap_Abstract $bootstrap = null): Yaf_Application
 * @method clearLastError(): Yaf_Application
 * @method environ(): void
 * @method execute(callable $entry, string ...$args): void
 * @method getAppDirectory(): Yaf_Application
 * @method getConfig(): Yaf_Config_Abstract
 * @method getDispatcher(): Yaf_Dispatcher
 * @method getLastErrorMsg(): string
 * @method getLastErrorNo(): int
 * @method getModules(): array
 * @method run(): void
 * @method setAppDirectory(string $directory): Yaf_Application
 * @method __destruct()
 */
final class Yaf_Application
{
    protected Yaf_Config_Abstract $config;
    protected Yaf_Dispatcher $dispatcher;
    protected static Yaf_Application $_app;
    protected string $_modules;
    protected bool $_running;
    protected bool $_environ;
//    protected $_err_no = "0";
//    protected $_err_msg = "";
}

