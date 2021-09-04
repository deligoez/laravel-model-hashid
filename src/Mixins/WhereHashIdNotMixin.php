<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIdNotMixin
{
    /**
     * Add a where not clause on the Hash Id to the query.
     *
     * @return \Closure
     */
    public function whereHashIdNot(): Closure
    {
        /**
         * Add a where not clause on the Hash Id to the query.
         *
         * @param  mixed  $id
         * @return $this
         */
        return function ($id) {
            return $this->whereKeyNot($this->getModel()->keyFromHashID($id));
        };
    }
}
