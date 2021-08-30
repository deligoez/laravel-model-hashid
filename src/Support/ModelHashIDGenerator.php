<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Str;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

class ModelHashIDGenerator
{
    public static function buildPrefixForModel(Model|string $model): string
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

        foreach($generators as $modelClassName => $generator) {
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

        return null;
    }
}
