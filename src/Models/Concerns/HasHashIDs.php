<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Models\Concerns;

use Config;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use Illuminate\Database\Eloquent\Builder;
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
     */
    public static function bootHasHashIDs(): void
    {
        Builder::macro('findByHashID', function ($id, $columns = ['*']) {
            return $this->find($this->getModel()->decodeHashID($id), $columns);
        });

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
