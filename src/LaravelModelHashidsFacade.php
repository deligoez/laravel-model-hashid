<?php

namespace Deligoez\LaravelModelHashids;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Deligoez\LaravelModelHashids\LaravelModelHashids
 */
class LaravelModelHashidsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-model-hashids';
    }
}
