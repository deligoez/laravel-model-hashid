<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrFailByHashIdMixin
{
    /**
     * Find a model by its Hash Id or throw an exception.
     */
    public function findOrFailByHashId(): Closure
    {
        /*
         * Find a model by its Hash Id or throw an exception.
         *
         * @param  mixed  $id
         * @param  array  $columns
         *
         * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
         *
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
         */
        return function ($id, $columns = ['*']) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->findOrFail($this->getModel()->keyFromHashId($id), $columns);
        };
    }
}
