<?php

/**
 * @method __construct()
 * @method appendBody(string $content, string $key): bool
 * @method clearBody(string $key): bool
 * @method clearHeaders(): void
 * @method getBody(string $key): mixed
 * @method getHeader(): void
 * @method prependBody(string $content, string $key): bool
 * @method response(): void
 * @method setAllHeaders(): void
 * @method setBody(string $content, string $name = ""): bool
 * @method setHeader(string $name, string $value, bool $replace): bool
 * @method setRedirect(string $url): bool
 * @method __toString(): string
 * @method __destruct()
 */
abstract class Yaf_Response_Abstract
{
    const DEFAULT_BODY = 'content';

    protected bool $_header;
    protected bool $_body;
}

