<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrNewByHashIdMixin
{
    public function findOrNewByHashId(): Closure
    {
        return function ($id, $columns = ['*']) {
            return $this->findOrNew($this->getModel()->keyFromHashId($id), $columns);
        };
    }
}
