<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIdNotMixin
{
    public function whereHashIdNot(): Closure
    {
        return fn (mixed $id) => $this->whereKeyNot($this->getModel()->keyFromHashID($id));
    }
}
