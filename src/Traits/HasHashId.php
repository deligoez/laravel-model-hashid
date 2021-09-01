<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

use Hashids\HashidsInterface;
use Deligoez\LaravelModelHashId\Support\ModelHashIdGenerator;

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
        $this->hashIdGenerator = ModelHashIdGenerator::build($this);
    }

    public static function keyFromHashID(string $hashID): ?int
    {
        $hashIDInstance = ModelHashIdGenerator::parseHashIDForModel($hashID);

        if ($hashIDInstance === null) {
            return null;
        }

        $generator = ModelHashIdGenerator::build(__CLASS__);

        return $generator->decode($hashIDInstance->hashIDForKey)[0];
    }

    public function getHashIdAttribute(): ?string
    {
        return ModelHashIdGenerator::forModel($this);
    }

    public function getHashIdRawAttribute(): string
    {
        return $this->hashIdGenerator->encode($this->getKey());
    }
}
