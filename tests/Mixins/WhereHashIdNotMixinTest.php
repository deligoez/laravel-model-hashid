<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class WhereHashIdNotMixinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_query_that_a_model_will_not_be_retrieved_by_its_hash_id(): void
    {
        // 1. Arrange 🏗
        /** @var ModelA $model1 */
        $model1 = ModelA::factory()->create();
        /** @var ModelA $model2 */
        $model2 = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $foundModel = ModelA::query()
            ->whereHashIdNot($model1->hashId)
            ->first();

        // 3. Assert ✅
        $this->assertTrue($model2->is($foundModel));
    }
}
