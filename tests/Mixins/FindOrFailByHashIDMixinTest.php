<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindOrFailByHashIDMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_find_or_fail_a_model_by_its_hashID(): void
    {
        // 1️⃣.1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 1️⃣.2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findOrFailByHashID($model->hashID);

        // 1️⃣.3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));

        // 2️⃣.1️⃣ Arrange 🏗
        $model->delete();

        // 2️⃣.3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣.2️⃣ Act 🏋🏻‍
        ModelA::findOrFailByHashID($model->hashID);
    }
}
