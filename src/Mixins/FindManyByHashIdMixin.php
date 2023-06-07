<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Mixins;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

/** @mixin \Illuminate\Database\Eloquent\Builder */
class FindManyByHashIdMixin
{
    /**
     * Find multiple models by their Hash Ids.
     */
    public function findManyByHashId(): Closure
    {
        /*
         * Find multiple models by their Hash Ids.
         *
         * @param  \Illuminate\Contracts\Support\Arrayable|array  $ids
         * @param  array  $columns
         * @return \Illuminate\Database\Eloquent\Collection
         */
        return function (Arrayable|array $ids, $columns = ['*']) {
            $ids = $ids instanceof Arrayable ? $ids->toArray() : $ids;
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            $ids = array_map(fn (string $hashId) => $this->getModel()->keyFromHashId($hashId), $ids);

            return $this->findMany($ids, $columns);
        };
    }
}
