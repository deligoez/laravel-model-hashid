<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIdMixin
{
    public function whereHashId(): Closure
    {
        return fn (mixed $id) => $this->whereKey($this->getModel()->keyFromHashID($id));
    }
}
