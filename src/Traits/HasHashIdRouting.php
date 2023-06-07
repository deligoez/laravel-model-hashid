<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasHashIdRouting
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->findByHashId($value);
    }

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey(): string
    {
        return $this->hashId;
    }
}
