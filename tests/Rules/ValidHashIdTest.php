<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Rules\ValidHashId;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

// Generic validation (no model)

it('passes for a valid hash id string', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $rule   = new ValidHashId();
    $passed = false;

    $rule->validate('token', $hashId, function () use (&$passed): void {
        $passed = true;
    });

    expect($passed)->toBeFalse();
});

it('fails for a completely invalid string', function (): void {
    $rule   = new ValidHashId();
    $failed = false;

    $rule->validate('token', 'not-a-valid-hash-id-at-all', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for an empty string', function (): void {
    $rule   = new ValidHashId();
    $failed = false;

    $rule->validate('token', '', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a non-string value', function (): void {
    $rule   = new ValidHashId();
    $failed = false;

    $rule->validate('token', 12345, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

// Model-specific validation

it('passes for a valid hash id matching the model', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $rule   = new ValidHashId(ModelA::class);
    $passed = false;

    $rule->validate('user_id', $hashId, function () use (&$passed): void {
        $passed = true;
    });

    expect($passed)->toBeFalse();
});

it('fails when prefix does not match the model', function (): void {
    Config::set(ConfigParameters::SALT, 'salt-for-model-a', ModelA::class);
    Config::set(ConfigParameters::PREFIX, 'usra', ModelA::class);
    Config::set(ConfigParameters::SALT, 'salt-for-model-b', ModelB::class);
    Config::set(ConfigParameters::PREFIX, 'usrb', ModelB::class);

    $modelB = ModelB::factory()->create();
    $hashId = $modelB->hashId;

    $rule   = new ValidHashId(ModelA::class);
    $failed = false;

    $rule->validate('user_id', $hashId, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a valid format but wrong characters', function (): void {
    $rule   = new ValidHashId(ModelA::class);
    $failed = false;

    $rule->validate('user_id', 'model_ZZZZZZZZZ', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('fails for a hash id from a different model', function (): void {
    Config::set(ConfigParameters::SALT, 'salt-for-model-a', ModelA::class);
    Config::set(ConfigParameters::SALT, 'salt-for-model-b', ModelB::class);

    $modelB = ModelB::factory()->create();
    $hashId = $modelB->hashId;

    $rule   = new ValidHashId(ModelA::class);
    $failed = false;

    $rule->validate('user_id', $hashId, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('throws InvalidArgumentException when model does not use HasHashId', function (): void {
    expect(fn () => new ValidHashId(Model::class))
        ->toThrow(InvalidArgumentException::class);
});
