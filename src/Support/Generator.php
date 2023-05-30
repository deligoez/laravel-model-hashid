<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Model;
use Str;

class Generator
{
    /**
     * Builds the model prefix according to the model or generic configuration.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function buildPrefixForModel(Model|string $model): string
    {
        Config::checkIfModelClassExist($model);

        if (Config::has(ConfigParameters::PREFIX, $model)) {
            return Config::get(ConfigParameters::PREFIX, $model);
        }

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

    /**
     * Generates the Hash Id for the given model.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
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

    /**
     * Parses the given Hash Id and returns a HashIdDTO.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function parseHashIDForModel(string $hashId, ?string $className = null): ?HashIdDTO
    {
        $generators = Config::get(ConfigParameters::MODEL_GENERATORS);

        foreach ($generators as $modelClassName => $generator) {
            $prefix = self::buildPrefixForModel($modelClassName);
            $separator = Config::get(ConfigParameters::SEPARATOR, $modelClassName);
            $length = (int) Config::get(ConfigParameters::LENGTH, $modelClassName);

            if ($prefix || $separator) {
                $hashIdForKeyArray = explode($prefix . $separator, $hashId);
            } else {
                $hashIdForKeyArray = ['', $hashId];
            }

            if (isset($hashIdForKeyArray[1]) && mb_strlen($hashIdForKeyArray[1]) === $length) {
                return new HashIdDTO(
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

        if ($genericPrefixLength === -1) {
            $genericPrefixLength = mb_strlen(self::buildPrefixForModel($className));
        }

        if ($genericLength + $genericPrefixLength + mb_strlen($genericSeparator) === mb_strlen($hashId)) {
            return new HashIdDTO(
                prefix: mb_substr($hashId, 0, $genericPrefixLength),
                separator: $genericSeparator,
                hashIdForKey: mb_substr($hashId, $genericLength * -1),
                modelClassName: null
            );
        }

        return null;
    }

    /**
     * Builds a Hash Id generator for the given model.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function build(Model|string $model): HashidsInterface
    {
        Config::checkIfModelClassExist($model);

        $salt = Config::get(ConfigParameters::SALT, $model);
        $length = Config::get(ConfigParameters::LENGTH, $model);
        $alphabet = Config::get(ConfigParameters::ALPHABET, $model);

        return new Hashids($salt, $length, $alphabet);
    }
}
