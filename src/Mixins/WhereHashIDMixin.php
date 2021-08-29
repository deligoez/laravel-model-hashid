<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIDMixin
{
    public function whereHashID(): Closure
    {
        return fn(mixed $id) => $this->whereKey($this->getModel()->decodeHashID($id));
    }
}
