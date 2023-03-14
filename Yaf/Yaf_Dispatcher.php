<?php

/**
 * @method __construct()
 * @method autoRender(bool $flag): Yaf_Dispatcher
 * @method catchException(bool $flag): Yaf_Dispatcher
 * @method disableView(): bool
 * @method dispatch(Yaf_Request_Abstract $request): Yaf_Response_Abstract
 * @method enableView(): Yaf_Dispatcher
 * @method flushInstantly(bool $flag): Yaf_Dispatcher
 * @method getApplication(): Yaf_Application
 * @method getDefaultAction(): string
 * @method getDefaultController(): string
 * @method getDefaultModule(): string
 * @method static getInstance(): Yaf_Dispatcher
 * @method getRequest(): Yaf_Request_Abstract
 * @method getRouter(): Yaf_Router
 * @method initView(string $templates_dir, array $options): Yaf_View_Interface
 * @method registerPlugin(Yaf_Plugin_Abstract $plugin): Yaf_Dispatcher
 * @method returnResponse(bool $flag): Yaf_Dispatcher
 * @method setDefaultAction(string $action): Yaf_Dispatcher
 * @method setDefaultController(string $controller): Yaf_Dispatcher
 * @method setDefaultModule(string $module): Yaf_Dispatcher
 * @method setErrorHandler(call $callback, int $error_types): Yaf_Dispatcher
 * @method setRequest(Yaf_Request_Abstract $request): Yaf_Dispatcher
 * @method setView(Yaf_View_Interface $view): Yaf_Dispatcher
 * @method throwException(bool $flag): Yaf_Dispatcher
 */
final class Yaf_Dispatcher
{
    protected Yaf_Router $_router;
    protected Yaf_View_Interface $_view;
    protected Yaf_Request_Abstract $_request;
    protected Yaf_Response_Abstract $_return_response;
    protected static Yaf_Dispatcher $_instance;
    protected bool $_auto_render;
    protected bool $_instantly_flush;
    protected array $_plugins;
    protected string $_default_module;
    protected string $_default_controller;
    protected string $_default_action;
}

