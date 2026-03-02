<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait SerializesHashId
{
    /**
     * Convert the model instance to an array, replacing the primary key with the hash ID.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if ($this->getKey() !== null) {
            $array[$this->getKeyName()] = $this->hashId;
        }

        return $array;
    }
}
