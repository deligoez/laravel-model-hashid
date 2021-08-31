<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Exceptions;

use Exception;

final class UnknownHashIDConfigParameterException extends Exception
{
    public static function make(string $parameter): self
    {
        return new UnknownHashIDConfigParameterException("Unknown HashID config parameter: '{$parameter}'.");
    }
}
