<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class FindManyByHashIdMixinTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_find_many_models_by_its_hashId(): void
    {
        // 1️⃣ Arrange 🏗
        $models = ModelA::factory()
                        ->count($this->faker->numberBetween(2, 5))
                        ->create();

        $modelHashId = $models->pluck('hashId')->toArray();

        // 2️⃣ Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashId($modelHashId);

        // 3️⃣ Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }

    /** @test */
    public function it_can_find_many_models_by_its_hashIds_from_specific_columns(): void
    {
        // 1️⃣ Arrange 🏗
        $models = ModelA::factory()
                        ->count($this->faker->numberBetween(2, 5))
                        ->create();

        $modelHashIDs = $models->pluck('hashId')->toArray();

        $selectedColumns = ['id'];

        // 2️⃣ Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashId($modelHashIDs, $selectedColumns);

        // 3️⃣ Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }
}
