<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can find many models by their hash ids', function (): void {
    $models = ModelA::factory()
        ->count(fake()->numberBetween(2, 5))
        ->create();

    $modelHashIds = $models->pluck('hashId')->toArray();

    $foundModels = ModelA::findManyByHashId($modelHashIds);

    expect($foundModels->pluck('id')->toArray())->toBe($models->pluck('id')->toArray());
});

it('can find many models by their hash ids from specific columns', function (): void {
    $models = ModelA::factory()
        ->count(fake()->numberBetween(2, 5))
        ->create();

    $modelHashIds = $models->pluck('hashId')->toArray();

    $foundModels = ModelA::findManyByHashId($modelHashIds, ['id']);

    expect($foundModels->pluck('id')->toArray())->toBe($models->pluck('id')->toArray());
});

it('returns empty collection when given an empty array', function (): void {
    ModelA::factory()->count(3)->create();

    $result = ModelA::findManyByHashId([]);

    expect($result)->toBeEmpty();
});

it('ignores invalid hash ids and returns only valid matches', function (): void {
    $models = ModelA::factory()->count(2)->create();

    $hashIds = [
        $models[0]->hashId,
        'totally-invalid-hash',
        $models[1]->hashId,
    ];

    $result = ModelA::findManyByHashId($hashIds);

    expect($result)->toHaveCount(2);
});
