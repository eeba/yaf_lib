<?php

/**
 * @method __construct(string $module_name, string $controller_name, string $action_name)
 */
final class Yaf_Route_Simple implements Yaf_Route_Interface
{
    protected string $controller;
    protected string $module;
    protected string $action;
}