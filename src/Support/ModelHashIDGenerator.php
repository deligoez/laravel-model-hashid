<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Str;
use Hashids\Hashids;
use ReflectionClass;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Model;

class ModelHashIDGenerator
{
    public static function buildPrefixForModel(Model | string $model): string
    {
        HashIDModelConfig::isModelClassExist($model);

        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLength = HashIDModelConfig::get(HashIDModelConfig::PREFIX_LENGTH, $model);
        $prefix = rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'));

        return match (HashIDModelConfig::get(HashIDModelConfig::PREFIX_CASE, $model)) {
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
        $separator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, $model);

        return "{$prefix}{$separator}{$hashID}";
    }

    public static function parseHashIDForModel(string $hashID): ?ModelHashID
    {
        $generators = HashIDModelConfig::get(HashIDModelConfig::GENERATORS);

        foreach ($generators as $modelClassName => $generator) {
            $prefix = self::buildPrefixForModel($modelClassName);
            $separator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, $modelClassName);
            $length = (int) HashIDModelConfig::get(HashIDModelConfig::LENGTH, $modelClassName);

            $hashIDForKeyArray = explode($prefix.$separator, $hashID);

            if (isset($hashIDForKeyArray[1]) && mb_strlen($hashIDForKeyArray[1]) === $length) {
                return new ModelHashID(
                    prefix: $prefix,
                    separator: $separator,
                    hashIDForKey: $hashIDForKeyArray[1],
                    modelClassName: $modelClassName
                );
            }
        }

        $genericLength = (int) HashIDModelConfig::get(HashIDModelConfig::LENGTH);
        $genericSeparator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR);
        $genericPrefixLength = HashIDModelConfig::get(HashIDModelConfig::PREFIX_LENGTH);

        if ($genericLength + $genericPrefixLength + mb_strlen($genericSeparator) === mb_strlen($hashID)) {
            return new ModelHashID(
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
        HashIDModelConfig::isModelClassExist($model);

        $salt = HashIDModelConfig::get(HashIDModelConfig::SALT, $model);
        $length = HashIDModelConfig::get(HashIDModelConfig::LENGTH, $model);
        $alphabet = HashIDModelConfig::get(HashIDModelConfig::ALPHABET, $model);

        return new Hashids($salt, $length, $alphabet);
    }
}
