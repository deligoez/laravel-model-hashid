<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Config;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Str;

class ModelHashIDGenerator
{
    public static function buildPrefixForModel(Model $model): string
    {
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLength = Config::get('hashids.prefix_length', 3); // for model or generic
        $prefix = rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'));

        return match (Config::get('hashids.prefix_case', 'lower')) {
            'upper' => Str::upper($prefix),
            'camel' => Str::camel($prefix),
            'snake' => Str::snake($prefix),
            'kebab' => Str::kebab($prefix),
            default => Str::lower($prefix),
        };
    }
    }
}
