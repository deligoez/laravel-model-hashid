<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Mixins;

use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;

class FindOrNewByHashIDMixinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_find_a_model_by_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hashID = $model->hashID;

        // 2️⃣ Act 🏋🏻‍
        /** @var ModelA $newModel */
        $foundModel = ModelA::findOrNewByHashID($hashID);

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /** @test */
    public function it_can_find_a_model_by_its_hashID_from_specific_columns(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hashID = $model->hashID;
        $selectedColumns = ['id'];

        // 2️⃣ Act 🏋🏻‍
        /** @var ModelA $newModel */
        $foundModel = ModelA::findOrNewByHashID($hashID, $selectedColumns);

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /** @test */
    public function it_can_new_a_model_if_hashID_not_found(): void
    {
        // 2️⃣ Act 🏋🏻‍
        /** @var ModelA $newModel */
        $newModel = ModelA::findOrNewByHashID('non-existing-hash-id');

        // 3️⃣ Assert ✅
        $this->assertFalse($newModel->exists);
    }
}
