<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Exceptions;

use Exception;

final class CouldNotDecodeHashIDException extends Exception
{
    public static function make(array $result): self
    {
        return new CouldNotDecodeHashIDException("Couldn't decode to HashID.");
    }
}
