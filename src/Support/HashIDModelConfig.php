<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class HashIDModelConfig
{
    public static function forModel(Model $model, string $config): string|int
    {
        if (! in_array($config, [
            'salt',
            'length',
            'alphabet',
            'prefix_length',
            'prefix_case',
            'separator',
            'generators',
        ], true)) {
            throw new RuntimeException("Unknown config parameter: '{$config}'.");
        }

        if (Arr::has(Config::get('hashids.generators'), get_class($model).'.'.$config)) {
            return Config::get('hashids.generators')[get_class($model)][$config];
        }

        return Config::get("hashids.{$config}");
    }
}
