<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\Config;

trait SavesHashId
{
    public static function bootSavesHashId(): void
    {
        static::created(
            function (Model $model) {
                $column = Config::get(Config::DATABASE_COLUMN, $model);

                $model
                    ->fill([$column => $model->hashId])
                    ->saveQuietly();
            },
        );
    }
}
