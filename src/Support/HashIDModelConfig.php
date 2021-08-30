<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use RuntimeException;

class HashIDModelConfig
{
    public static function get(Model|string $model, string $config): string|int
    {
        self::isConfigParameterDefined($config);
        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        // Return specific config for model if defined
        if (Arr::has(Config::get('hashids.generators'), $className.'.'.$config)) {
            return Config::get('hashids.generators')[$className][$config];
        }

        // Return generic config
        return Config::get("hashids.{$config}");
    }

    public static function set(Model|string $model, string $parameter, string|int $value): void
    {
        self::isConfigParameterDefined($parameter);
        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        $generatorsConfig = Config::get('hashids.generators');

        $generatorsConfig[$className][$parameter] = $value;

        Config::set('hashids.generators', $generatorsConfig);
    }

    private static function isConfigParameterDefined(string $config): void
    {
        if (!in_array($config, array_keys(Config::get('hashids')), true)) {
            throw new RuntimeException("Unknown config parameter: '{$config}'.");
        }
    }

    private static function isModelClassExist(Model|string $model): void
    {
        if (is_string($model) && !class_exists($model)) {
            throw new RuntimeException("Model not exists: '{$model}'.");
        }
    }
}
