<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Rules\HashIdExists;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

// Existence check

it('passes when hash id matches an existing model', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $rule   = new HashIdExists(ModelA::class);
    $passed = false;

    $rule->validate('user_id', $hashId, function () use (&$passed): void {
        $passed = true;
    });

    expect($passed)->toBeFalse();
});

it('fails when hash id is valid but model was deleted', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $model->delete();

    $rule   = new HashIdExists(ModelA::class);
    $failed = false;

    $rule->validate('user_id', $hashId, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a completely invalid hash id', function (): void {
    $rule   = new HashIdExists(ModelA::class);
    $failed = false;

    $rule->validate('user_id', 'totally-invalid', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a hash id from a different model class', function (): void {
    $modelB = ModelB::factory()->create();
    $hashId = $modelB->hashId;

    $rule   = new HashIdExists(ModelA::class);
    $failed = false;

    $rule->validate('user_id', $hashId, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a non-string value', function (): void {
    $rule   = new HashIdExists(ModelA::class);
    $failed = false;

    $rule->validate('user_id', 12345, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('throws InvalidArgumentException when model does not use HasHashId', function (): void {
    expect(fn () => new HashIdExists(Model::class))
        ->toThrow(InvalidArgumentException::class);
});

// Edge case: model-specific config

it('works with model-specific config', function (): void {
    Config::set(ConfigParameters::SALT, 'custom-salt-for-model-a', ModelA::class);
    Config::set(ConfigParameters::PREFIX, 'usr', ModelA::class);
    Config::set(ConfigParameters::SEPARATOR, '_', ModelA::class);

    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $rule   = new HashIdExists(ModelA::class);
    $passed = false;

    $rule->validate('user_id', $hashId, function () use (&$passed): void {
        $passed = true;
    });

    expect($passed)->toBeFalse()
        ->and($hashId)->toStartWith('usr_');
});
