<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

use Illuminate\Database\Eloquent\Model;
use Config;

class HashIDModelConfig
{
    public static function forModel(Model $model, string $config): string|int
    {
        return Config::get("hashids.{$config}");
    }
}
