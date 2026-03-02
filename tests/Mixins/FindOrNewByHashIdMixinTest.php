<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can find a model by its hash id', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $foundModel = ModelA::findOrNewByHashId($hashId);

    expect($model->is($foundModel))->toBeTrue();
});

it('can find a model by its hash id from specific columns', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $foundModel = ModelA::findOrNewByHashId($hashId, ['id']);

    expect($model->is($foundModel))->toBeTrue();
});

it('can new a model if hash id not found', function (): void {
    $newModel = ModelA::findOrNewByHashId('non-existing-hash-id');

    expect($newModel->exists)->toBeFalse();
});
