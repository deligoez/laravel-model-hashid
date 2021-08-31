<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;

trait SavesHashIDs
{
    public static function bootSavesHashIDs(): void
    {
        static::created(
            function (Model $model) {
                $column = HashIDModelConfig::get(HashIDModelConfig::DATABASE_COLUMN, $model);

                return $model
                    ->fill([$column => $model->hashID])
                    ->saveQuietly();
            },
        );
    }
}
