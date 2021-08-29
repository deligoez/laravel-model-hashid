<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Config;

class HashIDModelConfigTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_get_generic_config_for_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);
        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $modelSeparator = HashIDModelConfig::forModel($model, 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($genericSeparator, $modelSeparator);
    }
}
