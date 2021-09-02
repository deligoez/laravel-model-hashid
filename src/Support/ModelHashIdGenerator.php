<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Str;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Model;

class ModelHashIdGenerator
{
    /**
     * @throws \ReflectionException
     */
    public static function buildPrefixForModel(Model | string $model): string
    {
        HashIdModelConfig::isModelClassExist($model);

        $shortClassName = class_basename($model);
        $prefixLength = (int) HashIdModelConfig::get(HashIdModelConfig::PREFIX_LENGTH, $model);
        $prefix = $prefixLength < 0
            ? $shortClassName
            : rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'));

        return match (HashIdModelConfig::get(HashIdModelConfig::PREFIX_CASE, $model)) {
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
        $hashID = $model->hashIDRaw;
        $separator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, $model);

        return "{$prefix}{$separator}{$hashID}";
    }

    public static function parseHashIDForModel(string $hashID): ?ModelHashId
    {
        $generators = HashIdModelConfig::get(HashIdModelConfig::GENERATORS);

        foreach ($generators as $modelClassName => $generator) {
            $prefix = self::buildPrefixForModel($modelClassName);
            $separator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, $modelClassName);
            $length = (int) HashIdModelConfig::get(HashIdModelConfig::LENGTH, $modelClassName);

            $hashIDForKeyArray = explode($prefix.$separator, $hashID);

            if (isset($hashIDForKeyArray[1]) && mb_strlen($hashIDForKeyArray[1]) === $length) {
                return new ModelHashId(
                    prefix: $prefix,
                    separator: $separator,
                    hashIDForKey: $hashIDForKeyArray[1],
                    modelClassName: $modelClassName
                );
            }
        }

        $genericLength = (int) HashIdModelConfig::get(HashIdModelConfig::LENGTH);
        $genericSeparator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR);
        $genericPrefixLength = HashIdModelConfig::get(HashIdModelConfig::PREFIX_LENGTH);

        if ($genericLength + $genericPrefixLength + mb_strlen($genericSeparator) === mb_strlen($hashID)) {
            return new ModelHashId(
                prefix: mb_substr($hashID, 0, $genericPrefixLength),
                separator: $genericSeparator,
                hashIDForKey: mb_substr($hashID, $genericLength * -1),
                modelClassName: null
            );
        }

        return null;
    }

    public static function build(Model | string $model): HashidsInterface
    {
        HashIdModelConfig::isModelClassExist($model);

        $salt = HashIdModelConfig::get(HashIdModelConfig::SALT, $model);
        $length = HashIdModelConfig::get(HashIdModelConfig::LENGTH, $model);
        $alphabet = HashIdModelConfig::get(HashIdModelConfig::ALPHABET, $model);

        return new Hashids($salt, $length, $alphabet);
    }
}
