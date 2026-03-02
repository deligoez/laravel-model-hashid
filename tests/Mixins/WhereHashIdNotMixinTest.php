<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can query that a model will not be retrieved by its hash id', function (): void {
    $model1 = ModelA::factory()->create();
    $model2 = ModelA::factory()->create();

    $results = ModelA::query()
        ->whereHashIdNot($model1->hashId)
        ->get();

    expect($results)->toHaveCount(1)
        ->and($model2->is($results->first()))->toBeTrue();
});

it('returns all models when hash id is invalid', function (): void {
    ModelA::factory()->count(3)->create();

    $results = ModelA::query()->whereHashIdNot('totally-invalid')->get();

    expect($results)->toHaveCount(3);
});
