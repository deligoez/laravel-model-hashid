<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

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
     * Retrieve the model for a bound value.
     *
     * @param  Model|Relation  $query
     * @param  mixed  $value
     * @param  null  $field
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder|Relation
    {
        /* @var \Illuminate\Database\Eloquent\Builder $this */
        $id = $this->getModel()->keyFromHashId($value);

        return $query->where($field ?? $this->getRouteKeyName(), $id);
    }

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey(): string
    {
        return $this->hashId;
    }
}
