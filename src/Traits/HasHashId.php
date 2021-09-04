<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Hashids\HashidsInterface;
use Deligoez\LaravelModelHashId\Support\Generator;

trait HasHashId
{
    protected ?HashidsInterface $hashIdGenerator = null;

    /**
     * Initialize the HasHasId trait for an instance.
     *
     * @return void
     */
    public function initializeHasHashId(): void
    {
        $this->hashIdGenerator = Generator::build($this);
    }

    public static function keyFromHashID(string $hashId): ?int
    {
        $hashIdInstance = Generator::parseHashIDForModel($hashId);

        if ($hashIdInstance === null) {
            return null;
        }

        $generator = Generator::build(__CLASS__);

        return $generator->decode($hashIdInstance->hashIdForKey)[0];
    }

    public function getHashIdAttribute(): ?string
    {
        return Generator::forModel($this);
    }

    public function getHashIdRawAttribute(): string
    {
        return $this->hashIdGenerator->encode($this->getKey());
    }
}
