<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Support;

class ModelHashID
{
    public function __construct(
        public string $prefix,
        public string $separator,
        public string $hashIDForKey,
        public ?string $modelClassName
    ) {
    }
}
