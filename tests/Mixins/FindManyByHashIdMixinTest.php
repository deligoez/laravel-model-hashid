<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class FindManyByHashIdMixinTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function it_can_find_many_models_by_its_hash_id(): void
    {
        // 1. Arrange 🏗
        $models = ModelA::factory()
            ->count($this->faker->numberBetween(2, 5))
            ->create();

        $modelHashId = $models->pluck('hashId')->toArray();

        // 2. Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashId($modelHashId);

        // 3. Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_can_find_many_models_by_its_hash_ids_from_specific_columns(): void
    {
        // 1. Arrange 🏗
        $models = ModelA::factory()
            ->count($this->faker->numberBetween(2, 5))
            ->create();

        $modelHashIDs = $models->pluck('hashId')->toArray();

        $selectedColumns = ['id'];

        // 2. Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashId($modelHashIDs, $selectedColumns);

        // 3. Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }
}
