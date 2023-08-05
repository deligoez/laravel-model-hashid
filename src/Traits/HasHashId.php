<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Hashids\HashidsInterface;
use Deligoez\LaravelModelHashId\Support\Generator;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @property-read string $hashId
 * @property-read string $hashIdRaw
 */
trait HasHashId
{
    protected ?HashidsInterface $hashIdGenerator = null;

    /**
     * Initialize the HasHasId trait for an instance.
     *
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public function initializeHasHashId(): void
    {
        $this->hashIdGenerator = Generator::build($this);
    }

    /**
     * Get the Hash Id for the model.
     *
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public function getHashIdAttribute(): ?string
    {
        return Generator::forModel($this);
    }

    /**
     * Get the Raw Hash Id for the model.
     *
     * @return ?string
     */
    public function getHashIdRawAttribute(): ?string
    {
        $key = $this->getKey();

        return $key === null
            ? null
            : $this->hashIdGenerator->encode($this->getKey());
    }

    /**
     * Decode given Hash Id and return the model key.
     *
     *
     *
     * @throws \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public static function keyFromHashId(string $hashId): ?int
    {
        $hashIdInstance = Generator::parseHashIDForModel($hashId, __CLASS__);

        if ($hashIdInstance === null) {
            return null;
        }

        $generator = Generator::build(__CLASS__);

        return $generator->decode($hashIdInstance->hashIdForKey)[0] ?? null;
    }
}
