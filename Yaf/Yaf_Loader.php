<?php

/**
 * @method __construct()
 * @method autoload(): void
 * @method clearLocalNamespace(): void
 * @method static getInstance(): void
 * @method getLibraryPath(bool $is_global = false): Yaf_Loader
 * @method getLocalNamespace(): void
 * @method getNamespacePath(string $namespaces): string
 * @method getNamespaces(): array
 * @method static import(): void
 * @method isLocalName(): void
 * @method registerLocalNamespace(mixed $prefix): void
 * @method registerNamespace(string|array $namespaces, string $path): bool
 * @method setLibraryPath(string $directory, bool $is_global = false): Yaf_Loader
 */
final class Yaf_Loader
{
    protected static Yaf_Loader $_instance;
    protected string $_local_ns;
    protected string $_library;
    protected string $_global_library;
}

