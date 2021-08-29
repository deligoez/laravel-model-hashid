<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class FindManyByHashIDMixinTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_find_many_models_by_its_hashIDs(): void
    {
        // 1️⃣ Arrange 🏗
        $models = ModelA::factory()
                        ->count($this->faker->numberBetween(2, 5))
                        ->create();

        $modelHashIDs = $models->pluck('hashID')->toArray();

        // 2️⃣ Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashID($modelHashIDs);

        // 3️⃣ Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }
}
