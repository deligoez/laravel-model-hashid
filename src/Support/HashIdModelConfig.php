<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Config;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

class HashIdModelConfig
{
    public const CONFIG_FILE_NAME = 'model-hashid';

    public const SALT = 'salt';
    public const LENGTH = 'length';
    public const ALPHABET = 'alphabet';
    public const PREFIX_LENGTH = 'prefix_length';
    public const PREFIX_CASE = 'prefix_case';
    public const SEPARATOR = 'separator';
    public const DATABASE_COLUMN = 'database_column';
    public const GENERATORS = 'generators';

    public static array $parameters = [
        self::SALT,
        self::LENGTH,
        self::ALPHABET,
        self::PREFIX_LENGTH,
        self::PREFIX_CASE,
        self::SEPARATOR,
        self::DATABASE_COLUMN,
        self::GENERATORS,
    ];

    public static function get(string $parameter, Model | string | null $model = null): string | int | array
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            return Config::get(self::CONFIG_FILE_NAME.'.'.$parameter);
        }

        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        // Return specific config for model if defined
        if (Arr::has(Config::get(self::CONFIG_FILE_NAME. '.' . self::GENERATORS), $className.'.'.$parameter)) {
            return Config::get(self::CONFIG_FILE_NAME. '.' . self::GENERATORS)[$className][$parameter];
        }

        // Return generic config
        return Config::get(self::CONFIG_FILE_NAME.'.'.$parameter);
    }

    public static function set(string $parameter, string | int $value, Model | string | null $model = null): void
    {
        self::isParameterDefined($parameter);

        if ($model === null) {
            Config::set(self::CONFIG_FILE_NAME.'.'.$parameter, $value);

            return;
        }

        self::isModelClassExist($model);

        $className = $model instanceof Model ? get_class($model) : $model;

        $generatorsConfig = Config::get(self::CONFIG_FILE_NAME. '.'. self::GENERATORS);

        $generatorsConfig[$className][$parameter] = $value;

        Config::set(self::CONFIG_FILE_NAME. '.' . self::GENERATORS, $generatorsConfig);
    }

    /**
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function isParameterDefined(string $parameter): void
    {
        if (! in_array($parameter, self::$parameters, true)) {
            throw UnknownHashIdConfigParameterException::make($parameter);
        }
    }

    public static function isModelClassExist(Model | string $model): void
    {
        if (is_string($model) && ! class_exists($model)) {
            throw new ModelNotFoundException();
        }
    }
}