<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindOrFailByHashIDMixin
{
    public function findOrFailByHashID(): Closure
    {
        return fn ($id, $columns = ['*']) => $this->findOrFail($this->getModel()->decodeHashID($id), $columns);
    }
}
