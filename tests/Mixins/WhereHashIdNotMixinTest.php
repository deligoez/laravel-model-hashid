<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('can query that a model will not be retrieved by its hash id', function (): void {
    $model1 = ModelA::factory()->create();
    $model2 = ModelA::factory()->create();

    $foundModel = ModelA::query()
        ->whereHashIdNot($model1->hashId)
        ->first();

    expect($model2->is($foundModel))->toBeTrue();
});
