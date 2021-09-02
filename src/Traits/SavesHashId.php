<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\HashIdModelConfig;

trait SavesHashId
{
    public static function bootSavesHashId(): void
    {
        static::created(
            function (Model $model) {
                $column = HashIdModelConfig::get(HashIdModelConfig::DATABASE_COLUMN, $model);

                $model
                    ->fill([$column => $model->hashId])
                    ->saveQuietly();
            },
        );
    }
}
