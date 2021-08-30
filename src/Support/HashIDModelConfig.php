<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use RuntimeException;

class HashIDModelConfig
{
    public static function forModel(Model|string $model, string $config): string|int
    {
        // Check if given $config is defined on config file
        if (!in_array($config, array_keys(Config::get('hashids')), true)) {
            throw new RuntimeException("Unknown config parameter: '{$config}'.");
        }

        if ($model instanceof Model) {
            $className = get_class($model);
        } else {
            // Check if given FQCN exists
            if (!class_exists($model)) {
                throw new RuntimeException("Model not exists: '{$config}'.");
            }
            $className = $model;
        }

        // Return specific config for model if defined
        if (Arr::has(Config::get('hashids.generators'), $className.'.'.$config)) {
            return Config::get('hashids.generators')[$className][$config];
        }

        // Return generic config
        return Config::get("hashids.{$config}");
    }
}
