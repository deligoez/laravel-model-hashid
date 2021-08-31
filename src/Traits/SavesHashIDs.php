<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;
use Illuminate\Database\Eloquent\Model;

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
