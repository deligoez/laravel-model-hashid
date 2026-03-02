<?php

declare(strict_types=1);

use Config as LaravelConfig;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

it('can set generic config without model instance or class name', function (): void {
    LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::SEPARATOR, '@');

    Config::set(ConfigParameters::SEPARATOR, '*');

    expect(Config::get(ConfigParameters::SEPARATOR))->toEqual('*');
});

it('can get generic config without model instance or class name', function (): void {
    Config::set(ConfigParameters::SEPARATOR, '#');

    expect(Config::get(ConfigParameters::SEPARATOR))->toEqual('#');
});

it('can get generic config for different models', function (): void {
    Config::set(ConfigParameters::SEPARATOR, '#');

    $modelASeparator = Config::get(ConfigParameters::SEPARATOR, ModelA::class);
    $modelBSeparator = Config::get(ConfigParameters::SEPARATOR, ModelB::class);

    expect($modelASeparator)->toEqual('#')
        ->and($modelBSeparator)->toEqual('#')
        ->and($modelASeparator)->toEqual($modelBSeparator);
});

it('can get specific config for different models', function (): void {
    Config::set(ConfigParameters::SEPARATOR, '#');
    Config::set(ConfigParameters::SEPARATOR, '!', ModelA::class);
    Config::set(ConfigParameters::SEPARATOR, '@', ModelB::class);

    expect(Config::get(ConfigParameters::SEPARATOR, ModelA::class))->toEqual('!')
        ->and(Config::get(ConfigParameters::SEPARATOR, ModelB::class))->toEqual('@');
});

it('can get specific config via model instance or class name', function (): void {
    Config::set(ConfigParameters::SEPARATOR, '#');
    Config::set(ConfigParameters::SEPARATOR, '!', ModelA::class);

    $viaInstance  = Config::get(ConfigParameters::SEPARATOR, new ModelA());
    $viaClassName = Config::get(ConfigParameters::SEPARATOR, ModelA::class);

    expect($viaInstance)->toEqual('!')
        ->and($viaClassName)->toEqual('!')
        ->and($viaInstance)->toEqual($viaClassName);
});

it('can set specific config via model instance or class name', function (): void {
    Config::set(ConfigParameters::SEPARATOR, '#');
    Config::set(ConfigParameters::LENGTH, 5);

    Config::set(ConfigParameters::SEPARATOR, '!', ModelA::class);
    Config::set(ConfigParameters::LENGTH, 6, ModelA::class);

    Config::set(ConfigParameters::SEPARATOR, '@', ModelB::class);
    Config::set(ConfigParameters::LENGTH, 10, ModelB::class);

    expect(Config::get(ConfigParameters::SEPARATOR, ModelA::class))->toEqual('!')
        ->and(Config::get(ConfigParameters::LENGTH, ModelA::class))->toEqual(6)
        ->and(Config::get(ConfigParameters::SEPARATOR, ModelB::class))->toEqual('@')
        ->and(Config::get(ConfigParameters::LENGTH, ModelB::class))->toEqual(10);
});

it('throws a runtime exception for unknown parameters', function (): void {
    expect(fn () => Config::checkIfParameterDefined('unknown-config'))
        ->toThrow(UnknownHashIdConfigParameterException::class);
});

it('throws a runtime exception for class names that does not exist', function (): void {
    expect(fn () => Config::checkIfModelClassExists('class-that-does-not-exist'))
        ->toThrow(ModelNotFoundException::class);
});
