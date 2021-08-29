<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindByHashIDMixin
{
    public function findByHashID(): Closure
    {
        return fn(mixed $id, $columns = ['*']) => $this->find($this->getModel()->decodeHashID($id), $columns);
    }
}
