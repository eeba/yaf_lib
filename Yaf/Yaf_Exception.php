<?php

/**
 * @method __construct()
 * @method getPrevious(): void
 */
class Yaf_Exception extends Exception implements Throwable
{
    protected $message = "";
    protected $code;
    protected $file = "";
    protected $line;
    private array $trace = [];
    private string $string = "";
    private ?Throwable $previous = null;
}

