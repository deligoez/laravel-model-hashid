<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrFailByHashIdMixin
{
    public function findOrFailByHashId(): Closure
    {
        return function ($id, $columns = ['*']) {
            return $this->findOrFail($this->getModel()->keyFromHashID($id), $columns);
        };
    }
}
