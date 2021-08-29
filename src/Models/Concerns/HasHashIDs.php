<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Models\Concerns;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use Config;

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
    }
}
