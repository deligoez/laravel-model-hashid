<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrFailByHashIDMixin
{
    public function findOrFailByHashID(): Closure
    {
        return function ($id, $columns = ['*']) {
            return $this->findOrFail($this->getModel()->keyFromHashID($id), $columns);
        };
    }
}
