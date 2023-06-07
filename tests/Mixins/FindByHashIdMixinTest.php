<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class FindByHashIdMixinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_find_a_model_by_its_hash_id(): void
    {
        // 1. Arrange 🏗
        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;

        // 2. Act 🏋🏻‍
        $foundModel = ModelA::findByHashId($hashId);

        // 3. Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /**
     * @test
     */
    public function it_can_find_a_model_by_its_hash_id_from_specific_columns(): void
    {
        // 1. Arrange 🏗
        $model           = ModelA::factory()->create();
        $hashId          = $model->hashId;
        $selectedColumns = ['id'];

        // 2. Act 🏋🏻‍
        $foundModel = ModelA::findByHashId($hashId, $selectedColumns);

        // 3. Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /**
     * @test
     */
    public function it_returns_null_if_can_not_find_a_model_with_given_hash_id(): void
    {
        // 2. Act 🏋🏻‍
        $foundModel = ModelA::findByHashId('non-existing-hash-id');

        // 3. Assert ✅
        $this->assertNull($foundModel);
    }
}
