<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class WhereHashIDNotMixinTest extends TestCase
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
