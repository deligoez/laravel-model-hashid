<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class FindOrNewByHashIdMixin
{
    /**
     * Find a model by its Hash Id or return fresh model instance.
     */
    public function findOrNewByHashId(): Closure
    {
        /*
         * Find a model by its Hash Id or return fresh model instance.
         *
         * @param  mixed  $id
         * @param  array  $columns
         * @return \Illuminate\Database\Eloquent\Model|static
         */
        return function ($id, $columns = ['*']) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->findOrNew($this->getModel()->keyFromHashId($id), $columns);
        };
    }
}
