<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

class ModelHashId
{
    public function __construct(
        public string $prefix,
        public string $separator,
        public string $hashIdForKey,
        public ?string $modelClassName
    ) {
    }
}
