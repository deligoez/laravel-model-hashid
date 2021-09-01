<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Exceptions;

use Exception;

final class UnknownHashIdConfigParameterException extends Exception
{
    public static function make(string $parameter): self
    {
        return new UnknownHashIdConfigParameterException("Unknown HashId config parameter: '{$parameter}'.");
    }
}
