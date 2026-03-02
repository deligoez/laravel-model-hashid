<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can find or a model by its hash id', function (): void {
    $model = ModelA::factory()->create();

    $foundModel = ModelA::findOrByHashId($model->hashId);

    expect($model->is($foundModel))->toBeTrue();

    $model->delete();

    expect(fn () => ModelA::findOrByHashId($model->hashId, ['*'], function (): void {
        throw new RuntimeException();
    }))->toThrow(RuntimeException::class);
});

it('can find or a model by its hash id from specific columns', function (): void {
    $model = ModelA::factory()->create();

    $foundModel = ModelA::findOrByHashId($model->hashId, ['id']);

    expect($model->is($foundModel))->toBeTrue();

    $model->delete();

    expect(fn () => ModelA::findOrByHashId($model->hashId, ['id'], function (): void {
        throw new RuntimeException();
    }))->toThrow(RuntimeException::class);
});
