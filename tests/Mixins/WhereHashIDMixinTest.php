<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class WhereHashIDMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_query_a_model_by_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::query()
                            ->whereHashID($model->hashID)
                            ->first();

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }
}
