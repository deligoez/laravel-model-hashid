<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Traits;

use Deligoez\LaravelModelHashIDs\Tests\Models\ModelC;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SavesHashIDsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_saves_hash_id_after_a_model_is_created(): void
    {
        // 2️⃣ Act 🏋🏻‍
        $model = ModelC::factory()->create();

        // 3️⃣ Assert ✅
        $this->assertEquals($model->hash_id, ModelC::find($model->id)->hashID);
    }
}
