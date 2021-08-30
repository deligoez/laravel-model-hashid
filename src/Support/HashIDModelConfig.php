<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use RuntimeException;

class HashIDModelConfig
{
    public static function get(string $parameter, Model|string|null $model = null): string|int
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            return Config::get("hashids.{$parameter}");
        }

        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        // Return specific config for model if defined
        if (Arr::has(Config::get('hashids.generators'), $className.'.'.$parameter)) {
            return Config::get('hashids.generators')[$className][$parameter];
        }

        // Return generic config
        return Config::get("hashids.{$parameter}");
    }

    public static function set(string $parameter, string|int $value, Model|string|null $model = null): void
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            Config::set("hashids.{$parameter}", $value);
            return;
        }

        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        $generatorsConfig = Config::get('hashids.generators');

        $generatorsConfig[$className][$parameter] = $value;

        Config::set('hashids.generators', $generatorsConfig);
    }

    private static function isParameterDefined(string $parameter): void
    {
        if (!in_array($parameter, array_keys(Config::get('hashids')), true)) {
            throw new RuntimeException("Unknown parameter: '{$parameter}'.");
        }
    }

    private static function isModelClassExist(Model|string $model): void
    {
        if (is_string($model) && !class_exists($model)) {
            throw new RuntimeException("Model not exists: '{$model}'.");
        }
    }
}
