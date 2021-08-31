<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salt String
    |--------------------------------------------------------------------------
    |
    | This salt string is used for generating HashIDs and should be set
    | to a random string, otherwise these generated HashIDs will not be
    | safe. Please do this definitely before deploying your application!
    |
    */

    'salt' => env('HASHID_SALT', 'your-secret-salt-string'),

    /*
    |--------------------------------------------------------------------------
    | Raw HashID Length
    |--------------------------------------------------------------------------
    |
    | This is the length of the raw HashID. The model prefix, separator
    | and the raw HashID are combined all together. So the Model HashID
    | length is the sum of raw HashID, separator, and model prefix lengths.
    |
    | Default: 13
    |
    */

    'length' => 13,

    /*
    |--------------------------------------------------------------------------
    | HashID Alphabet
    |--------------------------------------------------------------------------
    |
    | This alphabet will be used to generate raw HashIDs. Please keep in mind
    | that it must contain at least 16 unique characters and can't contain spaces.
    |
    | Default: 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890'
    |
    */

    'alphabet' => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',

    /*
    |--------------------------------------------------------------------------
    | Model Prefix Length
    |--------------------------------------------------------------------------
    |
    | Here you can specify the length of the model prefix. By default it
    | will be generated from the first letters of short class name.
    | Set it -1 to use full short class name as prefix.
    | Set it 0 to not use any prefix at all.
    |
    | Default: 3
    |
    */

    'prefix_length' => 3,

    /*
    |--------------------------------------------------------------------------
    | Model Prefix Case
    |--------------------------------------------------------------------------
    |
    | Here you can set the case of the prefix. Please keep in mind that for
    | some prefix cases underscore ('_') characters will be added to the
    | prefix if your model is multi word.
    |
    | Supported prefix cases: "lower", "upper", "camel", "snake", "kebab",
    |                         "title", "studly", "plural_studly"
    |
    | Default: 'lower'
    |
    */

    'prefix_case' => 'lower',

    /*
    |--------------------------------------------------------------------------
    | HashID Model Prefix Separator
    |--------------------------------------------------------------------------
    |
    | Here you can set the separator for your HashIDs. The separator
    | will be added between model prefix and the raw HashID.
    |
    | Default: '_'
    |
    */

    'separator' => '_',

    /*
    |--------------------------------------------------------------------------
    | Model Specific Generators
    |--------------------------------------------------------------------------
    |
    | Here you can set specific HashID generators for individual Models.
    | Each one of the setting above can be defined per model. You can
    | see an example below as comment.
    |
    */

    'generators' => [
//        App\Models\User::class => [
//            'salt'          => 'your-model-specific-salt-string',
//            'length'        => 13,
//            'alphabet'      => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',
//            'prefix_length' => 3,
//            'prefix_case'   => 'lower',
//            'separator'     => '_',
//        ],
    ],
];
