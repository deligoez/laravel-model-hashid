<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class FindByHashIDMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_find_a_model_by_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hashID = $model->hashID;

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findByHashID($hashID);

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /** @test */
    public function it_returns_null_if_can_not_find_a_model_with_given_hashID(): void
    {
        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findByHashID('non-existing-hash-id');

        // 3️⃣ Assert ✅
        $this->assertNull($foundModel);
    }
}
