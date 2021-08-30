# Laravel Model HashIDs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deligoez/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashids)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/deligoez/laravel-model-hashids/run-tests?label=tests)](https://github.com/deligoez/laravel-model-hashids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/deligoez/laravel-model-hashids/Check%20&%20fix%20styling?label=code%20style)](https://github.com/deligoez/laravel-model-hashids/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/deligoez/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/deligoez/laravel-model-hashids)

---
This repo can be used to scaffold a Laravel package. Follow these steps to get started:

1. Press the "Use template" button at the top of this repo to create a new repo with the contents of this laravel-model-hashids
2. Run "./configure-laravel-model-hashids.sh" to run a script that will replace all placeholders throughout all the files
3. Remove this block of text.
4. Have fun creating your package.
5. If you need help creating a package, consider picking up our <a href="https://laravelpackage.training">Laravel Package Training</a> video course.
---

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require deligoez/laravel-model-hashids
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Deligoez\LaravelModelHashids\LaravelModelHashidsServiceProvider" --tag="laravel-model-hashids-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-model-hashids = new Deligoez\LaravelModelHashids();
echo $laravel-model-hashids->echoPhrase('Hello, Spatie!');
```

# Pitfalls

`ModelA` ve `ModelB` icin ayni prefix uzunlugu ve separator kulanildiginda, eger modeller icin farkli salt verilmisse: HashID'den dogru salt'a ulasmam mumkun olmayacak, cunku hangi salt'in kullaniclacagi ayirt edilebilir degil.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yunus Emre Deligöz](https://github.com/deligoez)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
