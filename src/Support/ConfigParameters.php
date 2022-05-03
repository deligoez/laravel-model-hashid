<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

class ConfigParameters
{
    /*
     * Hash Id configuration file name.
     */
    public const CONFIG_FILE_NAME = 'model-hashid';

    /*
     * Hash Id configuration key name for SALT.
     */
    public const SALT = 'salt';

    /*
     * Hash Id configuration key name for LENGTH.
     */
    public const LENGTH = 'length';

    /*
     * Hash Id configuration key name for ALPHABET.
     */
    public const ALPHABET = 'alphabet';

    /*
     * Hash Id configuration key name for PREFIX.
     */
    public const PREFIX = 'prefix';

    /*
     * Hash Id configuration key name for PREFIX_LENGTH.
     */
    public const PREFIX_LENGTH = 'prefix_length';

    /*
     * Hash Id configuration key name for PREFIX_CASE.
     */
    public const PREFIX_CASE = 'prefix_case';

    /*
     * Hash Id configuration key name for SEPARATOR.
     */
    public const SEPARATOR = 'separator';

    /*
     * Hash Id configuration key name for DATABASE_COLUMN.
     */
    public const DATABASE_COLUMN = 'database_column';

    /*
     * Hash Id configuration key name for GENERATORS.
     */
    public const MODEL_GENERATORS = 'model_generators';

    /*
     * All Hash Id configuration parameter keys.
     */
    public static array $parameters = [
        self::SALT,
        self::LENGTH,
        self::ALPHABET,
        self::PREFIX,
        self::PREFIX_LENGTH,
        self::PREFIX_CASE,
        self::SEPARATOR,
        self::DATABASE_COLUMN,
        self::MODEL_GENERATORS,
    ];
}
