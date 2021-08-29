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
    | HashID Length
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
];
