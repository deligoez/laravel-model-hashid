<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Hashids\HashidsInterface;
use Deligoez\LaravelModelHashIDs\Support\ModelHashIDGenerator;

trait HasHashIDs
{
    protected ?HashidsInterface $hashIDGenerator = null;

    /**
     * Initialize the HasHasIDs trait for an instance.
     *
     * @return void
     */
    public function initializeHasHashIDs(): void
    {
        $this->hashIDGenerator = ModelHashIDGenerator::build($this);
    }

    public static function keyFromHashID(string $hashID): ?int
    {
        $hashIDInstance = ModelHashIDGenerator::parseHashIDForModel($hashID);

        if ($hashIDInstance === null) {
            return null;
        }

        $generator = ModelHashIDGenerator::build(__CLASS__);

        return $generator->decode($hashIDInstance->hashIDForKey)[0];
    }

    public function getHashIDAttribute(): ?string
    {
        return ModelHashIDGenerator::forModel($this);
    }

    public function getHashIDRawAttribute(): string
    {
        return $this->hashIDGenerator->encode($this->getKey());
    }
}
