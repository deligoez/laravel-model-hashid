<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\Generator;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

/**
 * @mixin Model
 *
 * @property-read string $hashId
 * @property-read string $hashIdRaw
 */
trait HasHashId
{
    protected ?HashidsInterface $hashIdGenerator = null;

    /**
     * Initialize the HasHashId trait for an instance.
     *
     * @throws UnknownHashIdConfigParameterException
     */
    public function initializeHasHashId(): void
    {
        $this->hashIdGenerator = Generator::build($this);
    }

    /**
     * Get the Hash Id for the model.
     *
     * @throws UnknownHashIdConfigParameterException
     */
    public function getHashIdAttribute(): ?string
    {
        return Generator::forModel($this);
    }

    /**
     * Get the Raw Hash Id for the model.
     */
    public function getHashIdRawAttribute(): ?string
    {
        $key = $this->getKey();

        return $key === null
            ? null
            : $this->hashIdGenerator->encode($key);
    }

    /**
     * Decode given Hash Id and return the model key.
     *
     * @throws UnknownHashIdConfigParameterException
     */
    public static function keyFromHashId(string $hashId): ?int
    {
        $hashIdInstance = Generator::parseHashIDForModel($hashId, __CLASS__);

        if ($hashIdInstance === null) {
            return null;
        }

        $generator = Generator::build(__CLASS__);
        $decoded   = $generator->decode($hashIdInstance->hashIdForKey);

        return $decoded[0] ?? null;
    }
}
