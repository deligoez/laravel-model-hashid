<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class WhereHashIdNotMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_query_that_a_model_will_not_be_retrieved_by_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model1 = ModelA::factory()->create();
        $model2 = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::query()
                            ->whereHashIDNot($model1->hashID)
                            ->first();

        // 3️⃣ Assert ✅
        $this->assertTrue($model2->is($foundModel));
    }
}
