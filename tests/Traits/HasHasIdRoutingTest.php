<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Route;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HasHasIdRoutingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_resolve_a_hashID_via_route_model_binding(): void
    {
        // 1️⃣ Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashID = $model->hashID;

        Route::get('/model-a/{modelA}', function (ModelA $modelA) {
            return $modelA->toJson();
        })->middleware('bindings');

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashID}");

        // 3️⃣ Assert ✅
        $response
            ->assertOk()
            ->assertJsonFragment([
                'id'   => $model->getKey(),
                'name' => 'model-that-should-bind',
            ]);
    }

    /** @test */
    public function it_can_resolve_a_hashID_via_route_model_binding_using_custom_route_key_name(): void
    {
        // 1️⃣ Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-bind']);
        $hashID = $model->hashID;

        Route::model('hash_id', ModelA::class);

        Route::get('/model-a/{hash_id}', function ($modelBinding) {
            return $modelBinding->toJson();
        })->middleware('bindings');

        // 2️⃣ Act 🏋🏻‍
        $response = $this->getJson("/model-a/{$hashID}");

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
        })->middleware('bindings');

        // 3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣ Act 🏋🏻‍
        $this->getJson("/model-a/{$model->getKey()}");
    }
}
