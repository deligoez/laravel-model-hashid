<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Casts\HashIdCast;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('returns stored hash id on get', function (): void {
    $cast  = new HashIdCast();
    $model = ModelA::factory()->make();

    $result = $cast->get($model, 'hash_id', 'some_hash_value', []);

    expect($result)->toBe('some_hash_value');
});

it('returns null on get when null', function (): void {
    $cast  = new HashIdCast();
    $model = ModelA::factory()->make();

    $result = $cast->get($model, 'hash_id', null, []);

    expect($result)->toBeNull();
});

it('passes through string on set', function (): void {
    $cast  = new HashIdCast();
    $model = ModelA::factory()->make();

    $result = $cast->set($model, 'hash_id', 'existing_hash', []);

    expect($result)->toBe('existing_hash');
});

it('returns null on set when null', function (): void {
    $cast  = new HashIdCast();
    $model = ModelA::factory()->make();

    $result = $cast->set($model, 'hash_id', null, []);

    expect($result)->toBeNull();
});

it('converts integer to hash id on set', function (): void {
    $cast  = new HashIdCast();
    $model = ModelA::factory()->create();

    $result = $cast->set($model, 'hash_id', $model->getKey(), []);

    expect($result)->toBe($model->hashId);
});
