<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Support;

class ConfigParameters
{
    /*
     * Hash Id configuration file name.
     */
    public const string CONFIG_FILE_NAME = 'model-hashid';

    /*
     * Hash Id configuration key name for SALT.
     */
    public const string SALT = 'salt';

    /*
     * Hash Id configuration key name for LENGTH.
     */
    public const string LENGTH = 'length';

    /*
     * Hash Id configuration key name for ALPHABET.
     */
    public const string ALPHABET = 'alphabet';

    /*
     * Hash Id configuration key name for PREFIX.
     */
    public const string PREFIX = 'prefix';

    /*
     * Hash Id configuration key name for PREFIX_LENGTH.
     */
    public const string PREFIX_LENGTH = 'prefix_length';

    /*
     * Hash Id configuration key name for PREFIX_CASE.
     */
    public const string PREFIX_CASE = 'prefix_case';

    /*
     * Hash Id configuration key name for SEPARATOR.
     */
    public const string SEPARATOR = 'separator';

    /*
     * Hash Id configuration key name for DATABASE_COLUMN.
     */
    public const string DATABASE_COLUMN = 'database_column';

    /*
     * Hash Id configuration key name for GENERATORS.
     */
    public const string MODEL_GENERATORS = 'model_generators';

    /*
     * All Hash Id configuration parameter keys.
     */
    public const array PARAMETERS = [
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
