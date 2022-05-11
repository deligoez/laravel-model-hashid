<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

class HasHasIdRoutingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_resolve_a_hashId_via_route_model_binding(): void
    {
        // 1️⃣ Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashId = $model->hashId;

        Route::get('/model-a/{modelA}', function (ModelA $modelA) {
            return $modelA->toJson();
        })->middleware(SubstituteBindings::class);

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashId}");

        // 3️⃣ Assert ✅
        $response
            ->assertOk()
            ->assertJsonFragment([
                'id'   => $model->getKey(),
                'name' => 'model-that-should-bind',
            ]);
    }

    /** @test */
    public function it_can_resolve_a_hashId_via_route_model_binding_using_custom_route_key_name(): void
    {
        // 1️⃣ Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashId = $model->hashId;

        Route::model('hash_id', ModelA::class);

        Route::get('/model-a/{hash_id}', function ($modelBinding) {
            return $modelBinding->toJson();
        })->middleware(SubstituteBindings::class);

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashId}");

        // 3️⃣ Assert ✅
        $response
            ->assertOk()
            ->assertJsonFragment([
                'id'   => $model->getKey(),
                'name' => 'model-that-should-bind',
            ]);
    }

    /** @test */
    public function it_can_resolve_a_hashId_via_route_model_binding_using_negative_one_prefix_length(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, -1);

        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashId = $model->hashId;

        Route::get('/model-a/{modelA}', function (ModelA $modelA) {
            return $modelA->toJson();
        })->middleware(SubstituteBindings::class);

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashId}");

        // 3️⃣ Assert ✅
        $response
            ->assertOk()
            ->assertJsonFragment([
                'id'   => $model->getKey(),
                'name' => 'model-that-should-bind',
            ]);
    }

    /** @test */
    public function it_can_resolve_a_hashId_via_route_model_binding_using_negative_one_prefix_length_per_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 5);
        Config::set(ConfigParameters::PREFIX_LENGTH, -1, ModelA::class);

        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashId = $model->hashId;

        Route::get('/model-a/{modelA}', function (ModelA $modelA) {
            return $modelA->toJson();
        })->middleware(SubstituteBindings::class);

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashId}");

        // 3️⃣ Assert ✅
        $response
            ->assertOk()
            ->assertJsonFragment([
                                     'id'   => $model->getKey(),
                                     'name' => 'model-that-should-bind',
                                 ]);
    }

    /** @test */
    public function it_throws_a_model_not_found_exception_while_routing_with_model_key(): void
    {
        // 1️⃣ Arrange 🏗
        $this->withoutExceptionHandling();

        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);

        Route::model('model_a', ModelA::class);

        Route::get('/model-a/{model_a}', function (ModelA $modelBinding) {
            return $modelBinding->toJson();
        })->middleware(SubstituteBindings::class);

        // 3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣ Act 🏋🏻‍
        $this->getJson("/model-a/{$model->getKey()}");
    }
}
