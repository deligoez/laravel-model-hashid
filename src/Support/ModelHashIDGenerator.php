<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Str;
use Config;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

class ModelHashIDGenerator
{
    public static function buildPrefixForModel(Model $model): string
    {
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLength = Config::get('hashids.prefix_length', 3); // for model or generic
        $prefix = rtrim(mb_strimwidth($shortClassName, 0, $prefixLength, '', 'UTF-8'));

        return match (Config::get('hashids.prefix_case', 'lower')) {
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
    }
}
