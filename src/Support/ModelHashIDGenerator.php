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
        $prefixLength = Config::get('hashids.prefix_length', 3); // for model or generic
        $prefix = mb_strtolower(rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'))); // for model or generic

        return $prefix;
    }
}
