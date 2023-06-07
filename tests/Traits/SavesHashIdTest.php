<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelC;
use Deligoez\LaravelModelHashId\Tests\Models\ModelD;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

class SavesHashIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function it_saves_hash_id_after_a_model_is_created(): void
    {
        // 2. Act 🏋🏻‍
        $model = ModelC::factory()->create();

        // 3. Assert ✅
        $this->assertEquals($model->hash_id, ModelC::find($model->id)->hashId);
    }

    /**
     * @test
     */
    public function it_saves_hash_id_to_a_custom_column_after_a_model_is_created(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::DATABASE_COLUMN, 'hash', ModelD::class);

        // 2. Act 🏋🏻‍
        $model = ModelD::factory()->create();

        // 3. Assert ✅
        $this->assertEquals($model->hash, ModelD::find($model->id)->hashId);
    }
}
