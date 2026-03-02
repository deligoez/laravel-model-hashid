[![](https://banners.beyondco.de/Laravel%20Model%20HashId.png?theme=light&packageManager=composer+require&packageName=deligoez%2Flaravel-model-hashid&pattern=bubbles&style=style_2&description=Generate%2C+Save%2C+and+Route+Stripe-like+HashIds+for+Laravel+Eloquent+Models&md=1&showWatermark=0&fontSize=150px&images=hashtag&widths=400&heights=400)](https://github.com/deligoez/laravel-model-hashid)

<div align="center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deligoez/laravel-model-hashid.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashid)
[![Total Downloads](https://img.shields.io/packagist/dt/deligoez/laravel-model-hashid.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashid)
![Packagist](https://img.shields.io/packagist/l/deligoez/laravel-model-hashid)
[![CI](https://github.com/deligoez/laravel-model-hashid/actions/workflows/ci.yml/badge.svg)](https://github.com/deligoez/laravel-model-hashid/actions/workflows/ci.yml)
[![Open Source Love](https://badges.frapsoft.com/os/v3/open-source.svg?v=102)](https://github.com/ellerbrock/open-source-badge/)

</div>

Generate, save, and route [Stripe-like](https://gist.github.com/fnky/76f533366f75cf75802c8052b577e2a5) Hash Ids for your Laravel Eloquent Models.

Hash Ids are short, unique, and non-sequential identifiers that hide database row numbers from users. For more information, visit [hashids.org](https://hashids.org/).

For a `User` model with an id of `1234`, you can generate Hash Ids like `user_kqYZeLgo`.

```
https://your-app.com/user/1234          --> before
https://your-app.com/user/user_kqYZeLgo --> after
```

You have complete control over Hash Id length, prefix, separator, and alphabet. Check out the [configuration](#configuration) section for details.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Hash Id Generation](#hash-id-generation)
    - [Query Builder Functions](#query-builder-functions)
    - [Route Model Binding (Optional)](#route-model-binding-optional)
    - [Saving Hash Ids to Database (Optional)](#saving-hash-ids-to-database-optional)
- [Generic Hash Ids](#generic-hash-ids)
- [Blueprint Macro](#blueprint-macro)
- [Hash Id Cast](#hash-id-cast)
- [Serialization](#serialization)
- [Blade Directive](#blade-directive)
- [Decrypting Hash Ids in Form Requests](#decrypting-hash-ids-in-form-requests)
- [Artisan Commands](#artisan-commands)
- [Validation](#validation)
- [Hash Id Terminology](#hash-id-terminology)
- [Configuration](#configuration)
- [Upgrading](#upgrading)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Credits](#credits)
- [License](#license)

## Requirements

| Package | PHP        | Laravel    |
|---------|------------|------------|
| ^4.0    | ^8.3       | ^11.0, ^12.0 |
| ^3.0    | ^8.2       | ^9.0 - ^11.0 |
| ^2.0    | ^8.1       | ^9.0 - ^12.0 |
| ^1.0    | ^8.0       | ^8.0       |

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

### Hash Id Generation

Add the `HasHashId` trait to any Eloquent model:

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;

class User extends Model
{
    use HasHashId;
}
```

This gives you `hashId` and `hashIdRaw` attributes, plus a `keyFromHashId()` static method:

```php
$user = User::find(1234);

$user->hashId;    // 'user_kqYZeLgo'
$user->hashIdRaw; // 'kqYZeLgo'

User::keyFromHashId('user_kqYZeLgo'); // 1234
```

### Query Builder Functions

All finding-related query builder functions work with Hash Ids:

```php
// Find a model by its Hash Id
User::findByHashId('user_kqYZeLgo');

// Find multiple models by their Hash Ids
User::findManyByHashId(['user_kqYZeLgo', 'user_ZeLgokqY']);

// Find or throw ModelNotFoundException
User::findOrFailByHashId('user_kqYZeLgo');

// Find or execute a callback
User::findOrByHashId('user_kqYZeLgo');

// Find or return a new model instance
User::findOrNewByHashId('user_kqYZeLgo');

// Where clause using Hash Id
User::whereHashId('user_kqYZeLgo');

// Where not clause using Hash Id
User::whereHashIdNot('user_kqYZeLgo');
```

### Route Model Binding (Optional)

Add the `HasHashIdRouting` trait to enable route model binding with Hash Ids:

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;

class User extends Model
{
    use HasHashIdRouting;
}
```

#### Implicit Binding

```php
// GET /users/user_kqYZeLgo
Route::get('/users/{user}', function (User $user) {
    return $user;
});
```

#### Explicit Binding

Register a custom model key in your `RouteServiceProvider`:

```php
Route::model('hash_id', User::class);
```

```php
// GET /users/user_kqYZeLgo
Route::get('/users/{hash_id}', function (User $user) {
    return $user;
});
```

### Saving Hash Ids to Database (Optional)

Add the `SavesHashId` trait to automatically persist Hash Ids:

```php
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\SavesHashId;

class User extends Model
{
    use SavesHashId;
}
```

Set the `database_column` in your configuration file (default: `hash_id`). You can configure it globally or per model.

> Hash Id generation works **on the fly** -- saving to the database is not required for generation or decoding.

> Since Hash Id generation requires an integer model key, saving to the database results in an additional query after model creation.

## Generic Hash Ids

Use the `HashId` utility class to encode and decode integers without a model:

```php
use Deligoez\LaravelModelHashId\Support\HashId;

// Encode with default config
HashId::encode(1234);                                    // 'kqYZeLgo...'

// Encode with prefix and separator
HashId::encode(1234, prefix: 'tok', separator: '_');     // 'tok_kqYZeLgo...'

// Encode with custom salt, length, and alphabet
HashId::encode(1234, salt: 'custom', length: 8);

// Decode
HashId::decode('tok_kqYZeLgo...', prefix: 'tok');        // 1234

// Build a standalone generator
$generator = HashId::buildGenerator(salt: 'my-salt', length: 10);
```

All parameters are optional and fall back to config values.

## Blueprint Macro

A `hashId()` macro is available on the `Blueprint` class for migrations:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->hashId();              // nullable, unique string column (default: 'hash_id')
    $table->hashId('custom_hash'); // custom column name
    $table->timestamps();
});
```

The default column name comes from the `database_column` config value.

## Hash Id Cast

Use `HashIdCast` to cast attributes in your model:

```php
use Deligoez\LaravelModelHashId\Casts\HashIdCast;

class User extends Model
{
    use HasHashId;

    protected $casts = [
        'hash_id' => HashIdCast::class,
    ];
}
```

- `get()`: returns the stored string as-is
- `set()`: converts integers to a full Hash Id, passes strings through, and handles null

## Serialization

Add the `SerializesHashId` trait to replace the primary key with the Hash Id in serialized output (arrays and JSON):

```php
use Deligoez\LaravelModelHashId\Traits\SerializesHashId;

class User extends Model
{
    use HasHashId;
    use SerializesHashId;
}

$user = User::find(1234);
$user->toArray();  // ['id' => 'user_kqYZeLgo', 'name' => 'John', ...]
$user->toJson();   // {"id":"user_kqYZeLgo","name":"John",...}
```

This follows the Stripe pattern of exposing Hash Ids in API responses. It respects `$hidden` and `$visible` attributes.

## Blade Directive

Use the `@hashid` directive to output a model's Hash Id in Blade templates:

```blade
<a href="/users/@hashid($user)">{{ $user->name }}</a>
```

The output is XSS-safe via Laravel's `e()` helper.

## Decrypting Hash Ids in Form Requests

Add the `DecryptsHashIds` trait to a `FormRequest` to automatically convert Hash Id inputs to integer keys after validation:

```php
use Deligoez\LaravelModelHashId\Traits\DecryptsHashIds;

class UpdatePostRequest extends FormRequest
{
    use DecryptsHashIds;

    protected array $hashIds = [
        'user_id' => User::class,
        'post_id' => Post::class,
    ];

    public function rules(): array
    {
        return [
            'user_id' => ['required', new ValidHashId(User::class)],
            'post_id' => ['required', new ValidHashId(Post::class)],
        ];
    }
}

// In your controller, $request->user_id is now an integer
```

## Artisan Commands

Two Artisan commands are available for encoding and decoding Hash Ids:

```bash
# Encode a key to a Hash Id
php artisan hashid:encode "App\Models\User" 1234

# Decode a Hash Id (with explicit model)
php artisan hashid:decode "user_kqYZeLgo" "App\Models\User"

# Decode a Hash Id (auto-detect model from registered generators)
php artisan hashid:decode "user_kqYZeLgo"
```

## Validation

Two validation rules are provided for validating Hash Ids in form requests and validators.

### `ValidHashId` — Format Validation (No Database Hit)

Checks if a value is a valid Hash Id format. Optionally validates against a specific model (prefix + decode check).

```php
use Deligoez\LaravelModelHashId\Rules\ValidHashId;

// Generic: any decodable Hash Id
'token' => [new ValidHashId]

// Model-specific: must decode for User model
'user_id' => [new ValidHashId(User::class)]
```

### `HashIdExists` — Database Existence Check

Checks if a Hash Id corresponds to an existing database record. Equivalent to Laravel's `exists` rule for Hash Ids.

```php
use Deligoez\LaravelModelHashId\Rules\HashIdExists;

'user_id' => [new HashIdExists(User::class)]
```

Both rules throw an `InvalidArgumentException` if the given model does not use the `HasHashId` trait.

## Hash Id Terminology

A Hash Id consists of three parts:

| Part       | Example     | Required |
|------------|-------------|----------|
| Prefix     | `user`      | No       |
| Separator  | `_`         | No       |
| Raw Hash Id| `kqYZeLgo`  | Yes      |

You can generate Hash Ids with or without a prefix. Set `prefix_length` to `0` in the config to disable prefixes.

## Configuration

Publish the config file and customize:

```bash
php artisan vendor:publish --provider="Deligoez\LaravelModelHashId\LaravelModelHashIdServiceProvider" --tag="config"
```

| Option             | Default                                                       | Description                                                     |
|--------------------|---------------------------------------------------------------|-----------------------------------------------------------------|
| `salt`             | `'your-secret-salt-string'`                                   | Salt for Hash Id generation. **Change this before deploying.**  |
| `length`           | `13`                                                          | Raw Hash Id length (excluding prefix and separator)             |
| `alphabet`         | `'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890'` | Characters used in Hash Id generation (min 16 unique chars)  |
| `prefix_length`    | `3`                                                           | Prefix length from class name. `-1` for full name, `0` for none |
| `prefix_case`      | `'lower'`                                                     | Prefix case: `lower`, `upper`, `camel`, `snake`, `kebab`, `title`, `studly`, `plural_studly` |
| `separator`        | `'_'`                                                         | Separator between prefix and raw Hash Id                        |
| `database_column`  | `'hash_id'`                                                   | Database column name for `SavesHashId` trait                    |

### Model-Specific Configuration

Override any option per model in the `model_generators` array:

```php
'model_generators' => [
    App\Models\User::class => [
        'salt'            => 'user-specific-salt',
        'length'          => 8,
        'prefix_length'   => -1, // full class name as prefix
        'separator'       => '-',
    ],

    App\Models\Post::class => [
        'prefix' => 'article', // custom prefix (not generated from class name)
    ],
],
```

## Upgrading

If you are upgrading from v3 to v4, please see the [UPGRADE guide](UPGRADE.md) for a list of breaking changes.

## Testing

```bash
composer test
```

This runs the full quality gate: Rector, Pint, PHPStan, and Pest.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yunus Emre Deligoz](https://github.com/deligoez)
- [Scott Plunkett](https://github.com/plunkettscott)
- [Wade](https://github.com/striebwj)
- [Laravel Shift](https://github.com/laravel-shift)
- [Faruk](https://github.com/frkcn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
