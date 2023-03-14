<?php

/**
 * @method final private __construct()
 * @method display(string $tpl, array $parameters = []): bool
 * @method forward(string $action, array $parameters = []): bool
 * @method getInvokeArg(string $name): void
 * @method getInvokeArgs(): void
 * @method getModuleName(): string
 * @method getName(): string
 * @method getRequest(): Yaf_Request_Abstract
 * @method getResponse(): Yaf_Response_Abstract
 * @method getView(): Yaf_View_Interface
 * @method getViewpath(): string
 * @method init(): void
 * @method initView(array $options = []): void
 * @method redirect(string $url): bool
 * @method render(string $tpl, array $parameters = []): string
 * @method setViewpath(string $view_directory): void
 */
abstract class Yaf_Controller_Abstract
{
    public array $actions;
    protected string $_module;
    protected string $_controller;
    protected string $_name;
    protected array $_invoke_args;
    protected Yaf_Request_Abstract $_request;
    protected Yaf_Response_Abstract $_response;
    protected Yaf_View_Interface $_view;
}

