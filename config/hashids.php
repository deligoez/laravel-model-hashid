<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salt String for Hash ID
    |--------------------------------------------------------------------------
    |
    | This salt string is used for generating Hash IDs and should be set
    | to a random string, otherwise these generated HashIDs will not be
    | safe. Please do this definitely before deploying an application!
    |
    */

    'salt' => env('HASHID_SALT', 'your-secret-salt-string'),

    /*
    |--------------------------------------------------------------------------
    | HashIDRaw Length
    |--------------------------------------------------------------------------
    |
    | HashID Length
    |
    */

    'length' => 13,

    /*
    |--------------------------------------------------------------------------
    | HashID Alphabet
    |--------------------------------------------------------------------------
    |
    | HashID Alphabet
    |
    */

    'alphabet' => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',

    /*
    |--------------------------------------------------------------------------
    | HashID Model Prefix Length
    |--------------------------------------------------------------------------
    |
    | HashID Model Prefix Length
    | -1 for complete model name
    |
    */

    'prefix_length' => 3,

    /*
    |--------------------------------------------------------------------------
    | HashID Model Prefix Case
    |--------------------------------------------------------------------------
    |
    | HashID Model Prefix Case
    |
    | Supported prefix cases: "lower", "upper", "camel", "snake", "kebab",
    |                         "title", "studly", "plural_studly"
    |
    */

    'prefix_case' => 'lower',

    /*
    |--------------------------------------------------------------------------
    | HashID Model Prefix Separator
    |--------------------------------------------------------------------------
    |
    | HashID Model Prefix Separator
    |
    */

    'separator' => '_',

    /*
    |--------------------------------------------------------------------------
    | HashID Generators
    |--------------------------------------------------------------------------
    |
    | HashID Generators
    |
    */

    'generators' => [
//        App\Models\User::class => [
//            'salt'          => 'your-secret-salt-string',
//            'length'        => 13,
//            'alphabet'      => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',
//            'prefix_length' => 3,
//            'prefix_case'   => 'lower',
//            'separator'     => '_',
//        ],
    ],
];
