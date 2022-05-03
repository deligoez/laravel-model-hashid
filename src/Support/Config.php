<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config as LaravelConfig;

class Config
{
    /**
     * Get the specified Hash Id configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function get(string $parameter, Model | string | null $model = null): string | int | array
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            return LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter);
        };

        // Return the model specific configuration value if it exists.
        if (($specificConfig = self::getForModel($parameter, $model)) && !is_null($specificConfig)) {
            return $specificConfig;
        }

        // Return generic config
        return LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter);
    }

    /**
     * Gets a model specific configuration value, returning null if it doesn't exist.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function getForModel(string $parameter, Model | string $model): string | int | array | null
    {
        self::isParameterDefined($parameter);

        $className = $model instanceof Model ? get_class($model) : $model;

        // Get the model specific configuration value if it exists.
        if (Arr::has(LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS), $className.'.'.$parameter)) {
            return LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS)[$className][$parameter];
        }

        return null;
    }

    /**
     * Set a given Hash Id configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function set(string $parameter, string | int $value, Model | string | null $model = null): void
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter, $value);

            return;
        }

        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        $generatorsConfig = LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS);

        $generatorsConfig[$className][$parameter] = $value;

        LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS, $generatorsConfig);
    }


    /**
     * Check for recognized configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function isParameterDefined(string $parameter): void
    {
        if (! in_array($parameter, ConfigParameters::$parameters, true)) {
            throw UnknownHashIdConfigParameterException::make($parameter);
        }
    }

    /**
     * Check if given model class is exists.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     */
    public static function isModelClassExist(Model | string $model): void
    {
        if (is_string($model) && ! class_exists($model)) {
            throw new ModelNotFoundException();
        }
    }
}
