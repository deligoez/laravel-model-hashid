<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Support\Arr;

class HashIDModelConfig
{
    public static function forModel(Model $model, string $config): string|int
    {
        if(Arr::has(Config::get('hashids.generators'), get_class($model))) {
            return Config::get('hashids.generators')[get_class($model)][$config];
        }

        return Config::get("hashids.{$config}");
    }
}
