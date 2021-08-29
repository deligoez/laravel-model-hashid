<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Traits;

use Config;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Builder;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDNotMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindManyByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrNewByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrFailByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Exceptions\CouldNotDecodeHashIDException;

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
        $salt = Config::get('hashids.salt', '');
        $length = Config::get('hashids.length', 13);
        $alphabet = Config::get('hashids.alphabet', 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890');

        $this->hashIDGenerator = new Hashids($salt, $length, $alphabet);
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

    /**
     * @throws \Deligoez\LaravelModelHashIDs\Exceptions\CouldNotDecodeHashIDException
     */
    public function decodeHashID(string $hashid = null)
    {
        $decoded = $this->hashIDGenerator->decode($hashid ?? $this->hashID);

        if (empty($decoded)) {
            throw CouldNotDecodeHashIDException::make($decoded);
        }

        return $decoded[0];
    }

    public function encodeHashID(int $key = null): string
    {
        return $this->hashIDGenerator->encode($key ?? $this->getKey());
    }

    public function getHashIDAttribute(): string
    {
        return $this->hashIDGenerator->encode($this->getKey());
    }
}
