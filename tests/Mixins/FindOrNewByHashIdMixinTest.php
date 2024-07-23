<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Mixins;

use PHPUnit\Framework\Attributes\Test;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

class FindOrNewByHashIdMixinTest extends TestCase
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
        /** @var ModelA $foundModel */
        $foundModel = ModelA::findOrNewByHashId($hashId);

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
        /** @var ModelA $foundModel */
        $foundModel = ModelA::findOrNewByHashId($hashId, $selectedColumns);

        // 3. Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /**
     * @test
     */
    public function it_can_new_a_model_if_hash_id_not_found(): void
    {
        // 2. Act 🏋🏻‍
        /** @var ModelA $newModel */
        $newModel = ModelA::findOrNewByHashId('non-existing-hash-id');

        // 3. Assert ✅
        $this->assertFalse($newModel->exists);
    }
}
