<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use RuntimeException;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class FindOrByHashIdMixinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_find_or_a_model_by_its_hash_id(): void
    {
        // 1️⃣.1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 1️⃣.2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findOrByHashId($model->hashId);

        // 1️⃣.3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));

        // 2️⃣.1️⃣ Arrange 🏗
        $model->delete();

        // 2️⃣.3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣.2️⃣ Act 🏋🏻‍
        ModelA::findOrByHashId($model->hashId, function (): void {
            throw new RuntimeException();
        });
    }

    /**
     * @test
     */
    public function it_can_find_or_fail_a_model_by_its_hash_id_from_specific_columns(): void
    {
        // 1️⃣.1️⃣ Arrange 🏗
        $model           = ModelA::factory()->create();
        $selectedColumns = ['id'];

        // 1️⃣.2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findOrByHashId($model->hashId, $selectedColumns);

        // 1️⃣.3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));

        // 2️⃣.1️⃣ Arrange 🏗
        $model->delete();

        // 2️⃣.3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣.2️⃣ Act 🏋🏻‍
        ModelA::findOrByHashId($model->hashId, function (): void {
            throw new RuntimeException();
        });
    }
}
