<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindByHashIdMixin
{
    public function findByHashId(): Closure
    {
        return function (mixed $id, $columns = ['*']) {
            return $this->find($this->getModel()->keyFromHashId($id), $columns);
        };
    }
}
