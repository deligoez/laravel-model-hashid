<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Deligoez\LaravelModelHashId\Support\Generator;

/**
 * @implements CastsAttributes<string|null, string|int|null>
 */
class HashIdCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return Generator::forModel($model);
        }

        return (string) $value;
    }
}
