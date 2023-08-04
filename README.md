[![](https://banners.beyondco.de/Laravel%20Model%20HashId.png?theme=light&packageManager=composer+require&packageName=deligoez%2Flaravel-model-hashid&pattern=bubbles&style=style_2&description=Generate%2C+Save%2C+and+Route+Stripe-like+HashIds+for+Laravel+Eloquent+Models&md=1&showWatermark=0&fontSize=150px&images=hashtag&widths=400&heights=400)](https://github.com/deligoez/laravel-model-hashid)

<div align="center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deligoez/laravel-model-hashid.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashid)
[![Total Downloads](https://img.shields.io/packagist/dt/deligoez/laravel-model-hashid.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashid)
![Packagist](https://img.shields.io/packagist/l/deligoez/laravel-model-hashid)       
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=security_rating)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)   
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=coverage)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=ncloc)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=bugs)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)   
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=sqale_index)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=code_smells)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)    
[![tests](https://github.com/deligoez/laravel-model-hashid/actions/workflows/run-tests.yml/badge.svg)](https://github.com/deligoez/laravel-model-hashid/actions/workflows/run-tests.yml)
[![code style](https://github.com/deligoez/laravel-model-hashid/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/deligoez/laravel-model-hashid/actions/workflows/php-cs-fixer.yml)
[![types](https://github.com/deligoez/laravel-model-hashid/actions/workflows/phpstan.yml/badge.svg)](https://github.com/deligoez/laravel-model-hashid/actions/workflows/phpstan.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=deligoez_laravel-model-hashids&metric=alert_status)](https://sonarcloud.io/dashboard?id=deligoez_laravel-model-hashids)   
[![Open Source Love](https://badges.frapsoft.com/os/v3/open-source.svg?v=102)](https://github.com/ellerbrock/open-source-badge/)

</div>

Using this package you can generate, save and, route [Stripe-like](https://gist.github.com/fnky/76f533366f75cf75802c8052b577e2a5) Hash Ids for your Eloquent Models.

Hash Ids are short, unique, and non-sequential, and can generate unique Ids for URLs and hide database row numbers from
the user. For more information about Hash Ids please visit [hashids.org](https://hashids.org/).

With this package, you can customize Hash Id generation and add a model prefix and also separator.

For a `User` model with an id of `1234`, you can generate Hash Ids like `user_kqYZeLgo`.

So instead of;   
`https://your-endpoint.com/user/1234`   

You can have endpoints like;   
`https://your-endpoint.com/user/user_kqYZeLgo`

You have complete control over your Hash Id length and style. Check out the configuration file for more options.

## Table of contents

- [Features](#features)
- [Compatibility Table](#compatibility-table)
- [Installation](#installation)
- [Usage](#usage)
    - [Model Hash Id Generation](#model-hash-id-generation)
    - [Routing and Route Model Binding (Optional)](#routing-and-route-model-binding-optional)
    - [Saving Hash Ids to the Database (Optional)](#saving-hash-ids-to-the-database-optional)
- [Hash Id Terminology](#hash-id-terminology)
- [Configuration](#configuration)
- [Roadmap](#roadmap)
- [Testing](#testing)
- [Used By](#used-by)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Credits](#credits)
- [License](#license)

## Features

- Customizable Hash Id Generation
  - Hash Id Salt
  - Length
  - HashID Alphabet
  - Model Prefix Length and Case
  - Separator
- Model Specific Hash Id Generation
  - User-defined prefix per Model (optional)
  - Define separate configurations per Model
- Route (Model) Binding using Hash Ids (optional)
- Automatically save Hash Ids to the database (optional)

## Compatibility Table

The table below shows the compatibility across Laravel, PHP, and this package's **current version**.

| Package Version | Laravel version | PHP version | Compatible |
|-----------------|-----------------|-------------|------------|
| ^2.0            | 9.* , 10.*      | 8.1.*       |      ✅    |
| ^1.0            | 8.*             | 8.0.*       |      ✅    |
|                 | 8.*             | 7.4.*       |      ❌    |
|                 | 8.*             | 7.3.*       |      ❌    |
|                 | 7.x             | *           |      ❌    |

## Installation

1. Install via Composer:
    ```bash
    composer require deligoez/laravel-model-hashid
    ```
2. Publish the config file:
    ```bash
    php artisan vendor:publish --provider="Deligoez\LaravelModelHashId\LaravelModelHashIdServiceProvider" --tag="config"
    ```

## Usage

### Model Hash Id Generation

Add the `HasHashId` trait to any Laravel Model that should use Hash Ids.

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;

class ModelA extends Model
{
    use HasHashId;
    
    ...
}
```

#### Model Attributes and Static Model Functions

You will be able to use `hashId` and `hashIdRaw` attributes and `keyFromHashId()` static model function.

```php
$modelA = ModelA::find(1234);
$modelA->hashId;    // model_a_kqYZeLgo
$modelA->hashIdRaw; // kqYZeLgo

ModelA::keyFromHashId('model_a_kqYZeLgo') // 1234
```

#### Query Builder Functions

You can use all finding related Laravel query builder functions with Hash Ids.  

```php
// Find a model by its Hash Id.
ModelA::findByHashId('model_a_kqYZeLgo');

// Find multiple models by their Hash Ids.
ModelA::findManyByHashId(['model_a_kqYZeLgo', 'model_a_ZeLgokqY']);

// Find a model by its Hash Id or throw an exception.
ModelA::findOrFailByHashId('model_a_kqYZeLgo');

// Find a model by its Hash Id or or call a callback.
ModelA::findOrByHashId('model_a_kqYZeLgo');

// Find a model by its Hash Id or return fresh model instance.
ModelA::findOrNewByHashId('model_a_kqYZeLgo');

// Add a where clause on the Hash Id to the query.
ModelA::whereHashId('model_a_kqYZeLgo');

// Add a where not clause on the Hash Id to the query.
ModelA::whereHashIdNot('model_a_kqYZeLgo');
```

### Routing and Route Model Binding (Optional)

Simply add the `HasHashIdRouting` trait to your model that you want to route using Hash Ids.

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;

class ModelA extends Model
{
    use HasHashIdRouting;
    
    ...
}
```

#### Route Model Binding (Implicit)

You can define a route and/or controller method like this by Laravel conventions.

```php
// You can call this route with a Hash Id: your-endpoint.com/model-a/model_a_kqYZeLgo
Route::get('/model-a/{modelA}', function (ModelA $modelA) {
    // Your ModelA instance
    $modelA;
});
```
#### Route Model Binding (Explicit)

You can also define a custom model key on your `RouteServiceProvider`.

```php
Route::model('hash_id', ModelA::class);
```

```php
// You can call this route with a Hash Id: your-endpoint.com/model-a/model_a_kqYZeLgo
Route::get('/model-a/{hash_id}', function ($modelBinding) {
    // Your ModelA instance
    $modelBinding;
});
```

### Saving Hash Ids to the Database (Optional)

You can add the `SavesHashId` Trait to any of your Laravel Model that should save the generated Hash Ids.

After that, you should set `database_column` setting in the configuration file. You can define `database_column` setting
per model separately or for all of your models.

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\SavesHashId;

class ModelA extends Model
{
    use SavesHashId;
    
    ...
}
```

> All Hash Id generation or decoding methods work ON-THE-FLY. So you DO NOT need to save Hash Ids to the database for that reason.

> Since generating a Hash Id requires an integer model id/key, remember that storing the Hash Ids to a database will result in an additional database query.

## Hash Id Terminology

A typical Hash Id consist of 3 parts.

- Model Prefix (`model_a`)
- Separator (`_`)
- Raw Hash Id (`kqYZeLgo`)

Model Prefix and Separator are OPTIONAL. You can generate Hash Ids that only contains Raw Hash Ids.

## Configuration

This is the contents of the published config file:

```php
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
    | This alphabet will generate raw HashIDs. Please keep in mind that it
    | must contain at least 16 unique characters and can't contain spaces.
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
    | Here you can specify the length of the model prefix. By default, they
    | will generate it from the first letters of short class name.
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
    | some prefix cases, underscore (‘_’) characters will be added to the
    | prefix if your model name is multi word.
    |
    | Default: 'lower'
    |
    | Supported: "lower", "upper", "camel", "snake", "kebab",
    |            "title", "studly", "plural_studly"
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
    | HashID Database Column
    |--------------------------------------------------------------------------
    |
    | By using `SavesHashIDs` trait, you can save model HashIDs to database.
    | Here you can set the database column name for HashIDs to save.
    |
    | Default: 'hash_id'
    |
    */

    'database_column' => 'hash_id',

    /*
    |--------------------------------------------------------------------------
    | Model Specific Generators
    |--------------------------------------------------------------------------
    |
    | Here you can set specific HashID generators for individual Models.
    | Each one of the setting above can be defined per model. You can
    | see an example below as a comment.
    |
    */

    'model_generators' => [
        // App\Models\User::class => [
        //     'salt'            => 'your-model-specific-salt-string',
        //     'length'          => 13,
        //     'alphabet'        => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890',
        //     'prefix_length'   => 3,
        //     'prefix_case'     => 'lower',
        //     'separator'       => '_',
        //     'database_column' => 'hash_id',
        // ],

        // App\Models\Post::class => [
        //     'salt'            => 'your-model-specific-salt-string',
        //     'length'          => 13,
        //     'alphabet'        => 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890', 
        //     'prefix'          => 'abc', // Custom prefix that is not auto-generated
        //     'separator'       => '_',
        // ],
    ],
];
```

## Roadmap
- [x] Custom Model Prefixes (Not generated from a Model name) (Thanks to @plunkettscott)
- [ ] Hash Id Validation  Rules
- [ ] Generic Generators (Not bound to a Laravel Model)

## Testing

```bash
composer test
```

## Used By

This project is used by the following companies/products, you can add yours too:

- [blindnote.com](https://blindnote.com/)
- [tarfin.com](https://tarfin.com/)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yunus Emre Deligöz](https://github.com/deligoez)
- [Scott Plunkett](https://github.com/plunkettscott)
- [Wade](https://github.com/striebwj)
- [Laravel Shift](https://github.com/laravel-shift)
- [Faruk](https://github.com/frkcn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
