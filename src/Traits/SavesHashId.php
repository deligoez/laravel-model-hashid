<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

trait SavesHashId
{
    /**
     * Boot the SavesHashId trait for a model.
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function bootSavesHashId(): void
    {
        static::created(
            function (Model $model): void {
                $column = Config::get(ConfigParameters::DATABASE_COLUMN, $model);

                $model
                    ->fill([$column => $model->hashId])
                    ->saveQuietly();
            },
        );
    }
}
