<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindManyByHashIdMixin
{
    public function findManyByHashId(): Closure
    {
        return function (Arrayable | array $ids, $columns = ['*']) {
            $ids = $ids instanceof Arrayable ? $ids->toArray() : $ids;
            $ids = array_map(fn (string $hashId) => $this->getModel()->keyFromHashID($hashId), $ids);

            return $this->findMany($ids, $columns);
        };
    }
}
