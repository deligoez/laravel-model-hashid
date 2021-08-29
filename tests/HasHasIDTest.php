<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests;

use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Config;
class HasHasIDTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function model_hashID_salt_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hash = $model->hashID;

        // 2️⃣ Act 🏋🏻‍
        Config::set('hashids.salt', Str::random());

        // 3️⃣ Assert ✅
        $newHash = ModelA::findOrFail($model->getKey())->hashID;
        $this->assertNotEquals($hash, $newHash);
    }
}
