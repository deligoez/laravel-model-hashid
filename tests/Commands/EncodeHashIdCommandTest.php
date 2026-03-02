<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('encodes a key to a hash id', function (): void {
    $model = ModelA::factory()->create();

    $this->artisan('hashid:encode', ['model' => ModelA::class, 'id' => $model->getKey()])
        ->expectsOutput($model->hashId)
        ->assertSuccessful();
});

it('fails for non-existent class', function (): void {
    $this->artisan('hashid:encode', ['model' => 'App\\Models\\NonExistent', 'id' => 1])
        ->expectsOutputToContain('does not exist')
        ->assertFailed();
});

it('fails for model without HasHashId trait', function (): void {
    $this->artisan('hashid:encode', ['model' => \Illuminate\Database\Eloquent\Model::class, 'id' => 1])
        ->expectsOutputToContain('does not use the HasHashId trait')
        ->assertFailed();
});
