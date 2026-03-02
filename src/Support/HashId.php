<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

use Hashids\Hashids;
use Hashids\HashidsInterface;

class HashId
{
    /**
     * Encode an integer into a hash ID string.
     */
    public static function encode(
        int $id,
        ?string $prefix = null,
        ?string $separator = null,
        ?string $salt = null,
        ?int $length = null,
        ?string $alphabet = null,
    ): string {
        $generator = self::buildGenerator($salt, $length, $alphabet);
        $raw       = $generator->encode($id);

        if ($prefix === null || $prefix === '') {
            return $raw;
        }

        $separator ??= (string) Config::get(ConfigParameters::SEPARATOR);

        return "{$prefix}{$separator}{$raw}";
    }

    /**
     * Decode a hash ID string back to an integer.
     */
    public static function decode(
        string $hashId,
        ?string $prefix = null,
        ?string $separator = null,
        ?string $salt = null,
        ?int $length = null,
        ?string $alphabet = null,
    ): ?int {
        $generator = self::buildGenerator($salt, $length, $alphabet);

        $raw = $hashId;

        if ($prefix !== null && $prefix !== '') {
            $separator ??= (string) Config::get(ConfigParameters::SEPARATOR);
            $expectedPrefix = $prefix.$separator;

            if (!str_starts_with($hashId, $expectedPrefix)) {
                return null;
            }

            $raw = mb_substr($hashId, mb_strlen($expectedPrefix));
        }

        $decoded = $generator->decode($raw);

        return $decoded[0] ?? null;
    }

    /**
     * Build a standalone Hashids generator instance.
     */
    public static function buildGenerator(
        ?string $salt = null,
        ?int $length = null,
        ?string $alphabet = null,
    ): HashidsInterface {
        $salt ??= (string) Config::get(ConfigParameters::SALT);
        $length ??= (int) Config::get(ConfigParameters::LENGTH);
        $alphabet ??= (string) Config::get(ConfigParameters::ALPHABET);

        return new Hashids($salt, $length, $alphabet);
    }
}
