<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Mixins;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindManyByHashIDMixin
{
    public function findManyByHashID(): Closure
    {
        return function (Arrayable|array $ids, $columns = ['*']) {
            $ids = $ids instanceof Arrayable ? $ids->toArray() : $ids;
            $ids = array_map(fn (string $hashID) => $this->getModel()->decodeHashID($hashID), $ids);

            return $this->findMany($ids, $columns);
        };
    }
}
