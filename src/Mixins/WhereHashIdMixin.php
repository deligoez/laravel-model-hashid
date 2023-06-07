<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIdMixin
{
    /**
     * Add a where clause on the Hash Id to the query.
     */
    public function whereHashId(): Closure
    {
        /*
         * Add a where clause on the Hash Id to the query.
         *
         * @param  mixed  $id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        return function ($id) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->whereKey($this->getModel()->keyFromHashId($id));
        };
    }
}
