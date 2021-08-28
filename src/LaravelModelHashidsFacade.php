<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Deligoez\LaravelModelHashids\LaravelModelHashIDs
 */
class LaravelModelHashIDsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-model-hashids';
    }
}
