<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrByHashIdMixin
{
    /**
     * Find a model by its Hash Id or call a callback.
     */
    public function findOrByHashId(): Closure
    {
        /*
         * Find a model by its Hash Id or call a callback.
         *
         * @param  mixed  $id
         * @param  array  $columns
         *
         * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
         *
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
         */
        return function ($id, $columns = ['*'], Closure $callback = null) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->findOr($this->getModel()->keyFromHashId($id), $columns, $callback);
        };
    }
}
