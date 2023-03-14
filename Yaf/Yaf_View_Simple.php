<?php

/**
 * @method __construct(string $template_dir, array $options)
 * @method __get(string $name): mixed
 * @method __set(string $name, mixed $value): mixed
 * @method __isset(string $name): void
 * @method eval(string $tpl_content, array $tpl_vars): string
 * @method assignRef(string $name, mixed &$value): bool
 * @method clear(string $name): bool
 * @method assign(string $name, mixed $value): bool
 * @method display(string $tpl, array $tpl_vars): bool
 * @method getScriptPath(): string
 * @method render(string $tpl, array $tpl_vars): string
 * @method setScriptPath(string $template_dir): bool
 */
class Yaf_View_Simple implements Yaf_View_Interface
{
    protected array $_tpl_vars;
    protected string $_tpl_dir;
}