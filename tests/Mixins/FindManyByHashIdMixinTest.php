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
