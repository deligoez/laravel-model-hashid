<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class WhereHashIDNotMixin
{
    public function whereHashIDNot(): Closure
    {
        return fn (mixed $id) => $this->whereKeyNot($this->getModel()->keyFromHashID($id));
    }
}
