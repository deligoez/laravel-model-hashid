<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasHashIDRouting
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field) {
            return parent::resolveRouteBinding($value, $field);
        }

        return $this->findByHashID($value);
    }
}
