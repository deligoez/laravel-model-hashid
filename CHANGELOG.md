# Changelog

All notable changes to `laravel-model-hashid` will be documented in this file.

## 4.0.1 - 2026-03-04

### Fixed
- `DecryptsHashIds` trait now makes decoded values available via `validated()` in addition to `input()`. Previously, `merge()` only updated the input bag while `validated()` returned the original hash strings from the validator snapshot.

## 4.0.0 - 2026-03-03

### Breaking Changes
- Require PHP ^8.3 (dropped 8.2)
- Require Laravel ^11.0|^12.0 (dropped 9, 10)
- `ConfigParameters` is now `final` with a private constructor
- `HashIdDTO` is now a `readonly` class
- `Config::checkIfModelClassExist()` renamed to `Config::checkIfModelClassExists()`
- `HasHashIdRouting::getRouteKey()` return type changed from `string` to `mixed`

### Added
- `HashId` utility class — generic encode/decode without a model (`HashId::encode()`, `HashId::decode()`, `HashId::buildGenerator()`)
- `Blueprint::hashId()` macro — schema sugar for nullable, unique hash id columns in migrations
- `HashIdCast` — Eloquent cast for transparent hash id attribute storage
- `SerializesHashId` trait — replaces primary key with hash id in `toArray()` / `toJson()` output
- `@hashid` Blade directive — XSS-safe hash id output in templates
- `DecryptsHashIds` trait — auto-decodes hash id inputs to integer keys in `FormRequest`
- `hashid:encode` Artisan command — encode a key to a hash id via CLI
- `hashid:decode` Artisan command — decode a hash id to its key via CLI
- `ValidHashId` validation rule — format validation with optional model-specific check (no DB hit)
- `HashIdExists` validation rule — database existence check for Hash Ids
- Rector with PHP 8.3 target and Laravel rule sets
- Architecture tests (Pest Arch)
- Pest type coverage plugin
- Edge case tests for invalid hash ids, empty arrays, unsaved models
- Unified CI workflow with tiered quality gates (pint + rector -> larastan + pest matrix)

### Fixed
- Type casts in `Generator::build()` preventing `TypeError` with string config values
- Type casts in `Generator::parseHashIDForModel()` generic path for strict comparison
- Null guard in `Generator::parseHashIDForModel()` when `prefix_length` is `-1`
- `HasHashIdRouting::resolveRouteBindingQuery()` simplified to use `static::keyFromHashId()` directly
- `HasHashId::getHashIdRawAttribute()` removed redundant `getKey()` call
- `HasHashId::keyFromHashId()` returns null when decode yields empty array (#25)
- `FindOrByHashIdMixin` callback parameter position corrected

### Changed
- Test framework migrated from PHPUnit to Pest 4
- `ConfigParameters` constants are now typed (PHP 8.3 typed constants)
- `ConfigParameters::$parameters` static property replaced with `ConfigParameters::PARAMETERS` const array
- Larastan upgraded from v2 to v3
- PHPStan `ignoreErrors` migrated to identifier-based format
- `ServiceProvider::register()` uses `#[\Override]` attribute
- `Config.php` uses `::class` syntax instead of `get_class()`

### Removed
- SonarCloud integration
- Deprecated `.php-cs-fixer.php` config (replaced by Pint)
- Separate `run-tests.yml`, `phpstan.yml`, `pint.yml` workflows (unified into `ci.yml`)
- `phpstan-deprecation-rules` and `phpstan-phpunit` dev dependencies
- `nunomaduro/collision` dev dependency

## 3.1.1 - 2026-03-02
- Fix: Return null when hash id decode yields empty array (#25)

## 3.1.0 - 2025-02-26
- Add support for Laravel 12 by @laravel-shift in #30
- Add support for PHP 8.4 by @frkcn in #31

## 3.0.0 - 2024-07-23
- Add support for Laravel 11
- Update GitHub Actions for Laravel 11
- Update PHPUnit configuration and schema
- Bump dependency version constraints

## 2.4.1 - 2023-08-04
- Fix `Str` use path in `Generator.php` by @frkcn in #20

## 2.4.0 - 2023-06-08
- Fix if prefix and separator are both empty by @terranc in #18
- Resolve route binding query using `resolveRouteBindingQuery()` by @bensherred in #16
- Return null if the hash ID prefix does not match the model prefix by @bensherred in #17
- Replace `friendsofphp/php-cs-fixer` with `laravel/pint`

## 2.3.0 - 2023-02-04
- Add support for Laravel 10

## 2.2.0 - 2022-05-12
- Introduce `findOrByHashId` mixin
- Fix route model binding with a prefix length of -1 by @striebwj in #9

## 2.1.0 - 2022-05-03
- Add support for model-specific custom prefix by @plunkettscott in #6

## 2.0.0 - 2022-02-22
- Add PHP 8.1 support
- Add Laravel 9 support
- Drop Laravel 8 support

## 1.0.2 - 2021-09-05
- Type hints and docblock improvements
- Rename internal `ModelHashId` class to `HashIdDTO`
- Fix `keyFromHashId` method name

## 1.0.1 - 2021-09-04
- Fix config parameter name for model generators

## 1.0.0 - 2021-09-04
- Initial release
