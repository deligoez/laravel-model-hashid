<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use RuntimeException;

class HashIDModelConfig
{
    public static function forModel(Model $model, string $config): string|int
    {
        // Check if given $config is defined on config file
        if (!in_array($config, array_keys(Config::get('hashids')), true)) {
            throw new RuntimeException("Unknown config parameter: '{$config}'.");
        }

        // Return specific config for model if defined
        if (Arr::has(Config::get('hashids.generators'), get_class($model).'.'.$config)) {
            return Config::get('hashids.generators')[get_class($model)][$config];
        }

        // Return generic config
        return Config::get("hashids.{$config}");
    }
}
