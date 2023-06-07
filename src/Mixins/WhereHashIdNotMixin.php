<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIdNotMixin
{
    /**
     * Add a where not clause on the Hash Id to the query.
     */
    public function whereHashIdNot(): Closure
    {
        /*
         * Add a where not clause on the Hash Id to the query.
         *
         * @param  mixed  $id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        return function ($id) {
            /* @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->whereKeyNot($this->getModel()->keyFromHashId($id));
        };
    }
}
