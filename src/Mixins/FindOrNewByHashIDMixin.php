<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrNewByHashIDMixin
{
    public function findOrNewByHashID(): Closure
    {
        return function ($id, $columns = ['*']) {
            return $this->findOrNew($this->getModel()->keyFromHashID($id), $columns);
        };
    }
}
