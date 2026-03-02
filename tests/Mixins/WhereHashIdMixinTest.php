<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can query a model by its hash id', function (): void {
    $model = ModelA::factory()->create();

    $foundModel = ModelA::query()
        ->whereHashId($model->hashId)
        ->first();

    expect($model->is($foundModel))->toBeTrue();
});

it('returns empty result when hash id is invalid', function (): void {
    ModelA::factory()->create();

    $result = ModelA::query()->whereHashId('totally-invalid')->get();

    expect($result)->toBeEmpty();
});
