<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can find a model by its hash id', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $foundModel = ModelA::findByHashId($hashId);

    expect($model->is($foundModel))->toBeTrue();
});

it('can find a model by its hash id from specific columns', function (): void {
    $model  = ModelA::factory()->create();
    $hashId = $model->hashId;

    $foundModel = ModelA::findByHashId($hashId, ['id']);

    expect($model->is($foundModel))->toBeTrue();
});

it('returns null if can not find a model with given hash id', function (): void {
    $foundModel = ModelA::findByHashId('non-existing-hash-id');

    expect($foundModel)->toBeNull();
});
