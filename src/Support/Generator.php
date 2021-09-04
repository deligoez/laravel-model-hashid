<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Str;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Model;

class Generator
{
    /**
     * @throws \ReflectionException
     */
    public static function buildPrefixForModel(Model | string $model): string
    {
        Config::isModelClassExist($model);

        $shortClassName = class_basename($model);
        $prefixLength = (int) Config::get(ConfigParameters::PREFIX_LENGTH, $model);
        $prefix = $prefixLength < 0
            ? $shortClassName
            : rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'));

        return match (Config::get(ConfigParameters::PREFIX_CASE, $model)) {
            'upper'         => Str::upper($prefix),
            'camel'         => Str::camel($prefix),
            'snake'         => Str::snake($prefix),
            'kebab'         => Str::kebab($prefix),
            'title'         => Str::title($prefix),
            'studly'        => Str::studly($prefix),
            'plural_studly' => Str::pluralStudly($prefix),
            default         => Str::lower($prefix),
        };
    }

    public static function forModel(Model $model): ?string
    {
        if ($model->getKey() === null) {
            return null;
        }

        $prefix = self::buildPrefixForModel($model);
        $hashId = $model->hashIdRaw;
        $separator = Config::get(ConfigParameters::SEPARATOR, $model);

        return "{$prefix}{$separator}{$hashId}";
    }

    public static function parseHashIDForModel(string $hashId): ?ModelHashId
    {
        $generators = Config::get(ConfigParameters::MODEL_GENERATORS);

        foreach ($generators as $modelClassName => $generator) {
            $prefix = self::buildPrefixForModel($modelClassName);
            $separator = Config::get(ConfigParameters::SEPARATOR, $modelClassName);
            $length = (int) Config::get(ConfigParameters::LENGTH, $modelClassName);

            $hashIdForKeyArray = explode($prefix.$separator, $hashId);

            if (isset($hashIdForKeyArray[1]) && mb_strlen($hashIdForKeyArray[1]) === $length) {
                return new ModelHashId(
                    prefix: $prefix,
                    separator: $separator,
                    hashIdForKey: $hashIdForKeyArray[1],
                    modelClassName: $modelClassName
                );
            }
        }

        $genericLength = (int) Config::get(ConfigParameters::LENGTH);
        $genericSeparator = Config::get(ConfigParameters::SEPARATOR);
        $genericPrefixLength = Config::get(ConfigParameters::PREFIX_LENGTH);

        if ($genericLength + $genericPrefixLength + mb_strlen($genericSeparator) === mb_strlen($hashId)) {
            return new ModelHashId(
                prefix: mb_substr($hashId, 0, $genericPrefixLength),
                separator: $genericSeparator,
                hashIdForKey: mb_substr($hashId, $genericLength * -1),
                modelClassName: null
            );
        }

        return null;
    }

    public static function build(Model | string $model): HashidsInterface
    {
        Config::isModelClassExist($model);

        $salt = Config::get(ConfigParameters::SALT, $model);
        $length = Config::get(ConfigParameters::LENGTH, $model);
        $alphabet = Config::get(ConfigParameters::ALPHABET, $model);

        return new Hashids($salt, $length, $alphabet);
    }
}
