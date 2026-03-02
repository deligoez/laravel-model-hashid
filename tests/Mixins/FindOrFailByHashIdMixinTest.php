<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('can find or fail a model by its hash id', function (): void {
    $model = ModelA::factory()->create();

    $foundModel = ModelA::findOrFailByHashId($model->hashId);

    expect($model->is($foundModel))->toBeTrue();

    $model->delete();

    expect(fn () => ModelA::findOrFailByHashId($model->hashId))
        ->toThrow(ModelNotFoundException::class);
});

it('can find or fail a model by its hash id from specific columns', function (): void {
    $model = ModelA::factory()->create();

    $foundModel = ModelA::findOrFailByHashId($model->hashId, ['id']);

    expect($model->is($foundModel))->toBeTrue();

    $model->delete();

    expect(fn () => ModelA::findOrFailByHashId($model->hashId, ['id']))
        ->toThrow(ModelNotFoundException::class);
});
