<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

it('can resolve a hash id via route model binding', function (): void {
    ModelA::factory()->count(fake()->numberBetween(2, 5))->create();
    $model  = ModelA::factory()->create(['name' => 'model-that-should-bind']);
    $hashId = $model->hashId;

    Route::get('/model-a/{modelA}', fn (ModelA $modelA) => $modelA->toJson())
        ->middleware(SubstituteBindings::class);

    $this->getJson("/model-a/{$hashId}")
        ->assertOk()
        ->assertJsonFragment([
            'id'   => $model->getKey(),
            'name' => 'model-that-should-bind',
        ]);
});

it('can resolve a hash id via route model binding query', function (): void {
    $model  = ModelA::factory()->create(['name' => 'model-that-should-bind']);
    $hashId = $model->hashId;

    $resolvedModel = $model->resolveRouteBindingQuery(ModelA::query(), $hashId)->first();

    expect($resolvedModel)
        ->toBeInstanceOf(ModelA::class)
        ->getKey()->toEqual($model->getKey())
        ->name->toEqual('model-that-should-bind');
});

it('can resolve a hash id via route model binding using custom route key name', function (): void {
    ModelA::factory()->count(fake()->numberBetween(2, 5))->create();
    $model  = ModelA::factory()->create(['name' => 'model-that-should-bind']);
    $hashId = $model->hashId;

    Route::model('hash_id', ModelA::class);

    Route::get('/model-a/{hash_id}', fn ($modelBinding) => $modelBinding->toJson())
        ->middleware(SubstituteBindings::class);

    $this->getJson("/model-a/{$hashId}")
        ->assertOk()
        ->assertJsonFragment([
            'id'   => $model->getKey(),
            'name' => 'model-that-should-bind',
        ]);
});

it('can resolve a hash id via route model binding using negative one prefix length', function (): void {
    Config::set(ConfigParameters::PREFIX_LENGTH, -1);

    ModelA::factory()->count(fake()->numberBetween(2, 5))->create();
    $model  = ModelA::factory()->create(['name' => 'model-that-should-bind']);
    $hashId = $model->hashId;

    Route::get('/model-a/{modelA}', fn (ModelA $modelA) => $modelA->toJson())
        ->middleware(SubstituteBindings::class);

    $this->getJson("/model-a/{$hashId}")
        ->assertOk()
        ->assertJsonFragment([
            'id'   => $model->getKey(),
            'name' => 'model-that-should-bind',
        ]);
});

it('can resolve a hash id via route model binding using negative one prefix length per model', function (): void {
    Config::set(ConfigParameters::PREFIX_LENGTH, 5);
    Config::set(ConfigParameters::PREFIX_LENGTH, -1, ModelA::class);

    ModelA::factory()->count(fake()->numberBetween(2, 5))->create();
    $model  = ModelA::factory()->create(['name' => 'model-that-should-bind']);
    $hashId = $model->hashId;

    Route::get('/model-a/{modelA}', fn (ModelA $modelA) => $modelA->toJson())
        ->middleware(SubstituteBindings::class);

    $this->getJson("/model-a/{$hashId}")
        ->assertOk()
        ->assertJsonFragment([
            'id'   => $model->getKey(),
            'name' => 'model-that-should-bind',
        ]);
});

it('throws a model not found exception while routing with model key', function (): void {
    $this->withoutExceptionHandling();

    $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);

    Route::model('model_a', ModelA::class);

    Route::get('/model-a/{model_a}', fn (ModelA $modelBinding) => $modelBinding->toJson())
        ->middleware(SubstituteBindings::class);

    expect(fn () => $this->getJson("/model-a/{$model->getKey()}"))
        ->toThrow(ModelNotFoundException::class);
});
