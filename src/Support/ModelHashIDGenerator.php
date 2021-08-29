<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class ModelHashIDGenerator
{
    public static function buildPrefixForModel(Model $model): string
    {
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLenght = Config::get('hashids.prefix_lenght', 3); // for model or generic
        $prefix = mb_strtolower(rtrim(mb_strimwidth($shortClassName, 0, $prefixLenght, '', 'UTF-8'))); // for model or generic

        return $prefix;
    }
}
