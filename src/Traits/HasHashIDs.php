<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Config;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Builder;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDNotMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindManyByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Support\ModelHashIDGenerator;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrNewByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrFailByHashIDMixin;

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

    /**
     * Boot the HasHasIDs trait for a model.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public static function bootHasHashIDs(): void
    {
        Builder::mixin(new FindByHashIDMixin());
        Builder::mixin(new FindManyByHashIDMixin());
        Builder::mixin(new FindOrFailByHashIDMixin());
        Builder::mixin(new FindOrNewByHashIDMixin());
        Builder::mixin(new WhereHashIDMixin());
        Builder::mixin(new WhereHashIDNotMixin());
    }

    public static function keyFromHashID(string $hashID): int
    {
        $hashIDRaw = ModelHashIDGenerator::parseHashIDForModel($hashID)->hashIDForKey;

        $generator = ModelHashIDGenerator::build(__CLASS__);

        return $generator->decode($hashIDRaw)[0];
    }
    public function getHashIDRawAttribute(): string
    {
        return $this->hashIDGenerator->encode($this->getKey());
    }

    public function getHashIDAttribute(): string
    {
        return ModelHashIDGenerator::forModel($this);
    }
}
