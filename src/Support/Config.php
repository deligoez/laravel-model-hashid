<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config as LaravelConfig;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

class Config
{
    /**
     * Get the specified Hash Id configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function get(string $parameter, Model|string|null $model = null): string|int|array
    {
        self::checkIfParameterDefined($parameter);

        if ($model !== null) {
            $className = $model instanceof Model ? $model::class : $model;

            // Get the model specific configuration value if it exists.
            if (Arr::has(LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS), $className.'.'.$parameter)) {
                return LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS)[$className][$parameter];
            }
        }

        return LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter);
    }

    /**
     * Check if the specified Hash Id configuration exists.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function has(string $parameter, Model|string|null $model = null): bool
    {
        self::checkIfParameterDefined($parameter);

        if ($model !== null) {
            $className = $model instanceof Model ? $model::class : $model;

            return Arr::has(LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS), $className.'.'.$parameter);
        }

        return LaravelConfig::has(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter);
    }

    /**
     * Set a given Hash Id configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function set(string $parameter, string|int $value, Model|string|null $model = null): void
    {
        self::checkIfParameterDefined($parameter);

        if ($model === null) {
            LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.$parameter, $value);

            return;
        }

        self::checkIfModelClassExists($model);

        $className = $model instanceof Model ? $model::class : $model;

        $generatorsConfig = LaravelConfig::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS);

        $generatorsConfig[$className][$parameter] = $value;

        LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::MODEL_GENERATORS, $generatorsConfig);
    }

    /**
     * Check for recognized configuration value.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function checkIfParameterDefined(string $parameter): void
    {
        if (!in_array($parameter, ConfigParameters::PARAMETERS, true)) {
            throw UnknownHashIdConfigParameterException::make($parameter);
        }
    }

    /**
     * Check if the given model class exists.
     */
    public static function checkIfModelClassExists(Model|string $model): void
    {
        if (is_string($model) && !class_exists($model)) {
            throw new ModelNotFoundException();
        }
    }
}
