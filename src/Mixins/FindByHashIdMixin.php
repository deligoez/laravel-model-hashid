<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindByHashIdMixin
{
    /**
     * Find a model by its Hash Id.
     */
    public function findByHashId(): Closure
    {
        /*
         * Find a model by its Hash Id.
         *
         * @param  mixed  $id
         * @param  array  $columns
         * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
         */
        return function ($id, $columns = ['*']) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->find($this->getModel()->keyFromHashId($id), $columns);
        };
    }
}
