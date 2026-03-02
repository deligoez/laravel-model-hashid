# Upgrade Guide

## Upgrading from v3 to v4

### PHP and Laravel Requirements

| Requirement | v3         | v4           |
|-------------|------------|--------------|
| PHP         | ^8.2       | ^8.3         |
| Laravel     | ^9.0 - ^11.0 | ^11.0, ^12.0 |

If you are running PHP 8.2, you must upgrade to PHP 8.3 or later before upgrading this package.

If you are running Laravel 9 or 10, you must upgrade to Laravel 11 or later.

### Breaking Changes

#### `ConfigParameters` is now `final`

The `ConfigParameters` class is now `final` and has a private constructor. If you were extending this class, you should use the constants directly instead.

```php
// Before (v3): extending was possible
class MyConfigParameters extends ConfigParameters { ... }

// After (v4): use constants directly
ConfigParameters::SALT;
ConfigParameters::LENGTH;
```

#### `HashIdDTO` is now `readonly`

The `HashIdDTO` class is now a `readonly` class. If you were modifying its properties after construction, this is no longer allowed.

```php
// Before (v3): properties were mutable
$dto = new HashIdDTO(...);
$dto->prefix = 'new'; // worked

// After (v4): properties are immutable
$dto = new HashIdDTO(...);
$dto->prefix = 'new'; // Error: Cannot modify readonly property
```

#### `Config::checkIfModelClassExists()` renamed

The method `Config::checkIfModelClassExist()` has been renamed to `Config::checkIfModelClassExists()` (grammar fix).

```php
// Before (v3)
Config::checkIfModelClassExist($model);

// After (v4)
Config::checkIfModelClassExists($model);
```

#### `HasHashIdRouting::getRouteKey()` return type changed

The `getRouteKey()` method now returns `mixed` instead of `string`. This prevents a `TypeError` when the model has not been saved yet (hash id is `null`).

```php
// Before (v3): returned string
public function getRouteKey(): string

// After (v4): returns mixed
public function getRouteKey(): mixed
```

### Internal Improvements (Non-Breaking)

These changes are internal and should not affect your application, but are listed for completeness:

- **Type casts in `Generator::build()`**: Config values (`salt`, `length`, `alphabet`) are now explicitly cast to their expected types, preventing `TypeError` when config values are stored as strings.
- **Type casts in `Generator::parseHashIDForModel()`**: Generic path config values (`separator`, `prefix_length`) are now explicitly cast.
- **`HasHashId::getHashIdRawAttribute()`**: Removed a redundant `getKey()` call (uses cached variable instead).
- **`HasHashIdRouting::resolveRouteBindingQuery()`**: Simplified to use `static::keyFromHashId()` directly instead of going through `$this->getModel()`.
- **Test framework**: Tests migrated from PHPUnit to Pest 4.
- **Quality tooling**: Added Rector and updated PHPStan (Larastan v3). CI unified into a single workflow.
