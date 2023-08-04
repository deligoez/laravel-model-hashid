# Changelog

All notable changes to `laravel-model-hashid` will be documented in this file.


## 2.4.1 - 2023-08-04
* Str use path fixed in Generator.php by @frkcn in https://github.com/deligoez/laravel-model-hashid/pull/20

## 2.4.0 - 2023-06-08
* Fix if prefix and separator are both empty by @terranc in #18
* Feat: resolve route binding query using `resolveRouteBindingQuery()` by @bensherred in #16
* Test: Return null if the hash ID prefix does not match the model prefix @bensherred in #17

## 2.3.0 - 2023-01-04
- Add support for Laravel 9 by @laravel-shift in #14

## 2.2.0 - 2022-05-12
- Introduce `findOrByHashId` Mixin
- Fix route model binding with a prefix length of -1 by @striebwj in #9

## 2.1.0 - 2022-04-23
- Add support for model-specific prefix by @plunkettscott in #6

## 2.0.0 - 2022-02-22
- Add PHP 8.1 support
- Add Laravel 9 support
- Drop Laravel 8 support (Continue to use v1 if you're on Laravel 8)

## 1.0.2 - 2021-09-05
- Fixes, Type Hints

## 1.0.1 - 2021-09-04
- Fix config parameter for model generators

## 1.0.0 - 2021-09-04
- Initial release 🎉
