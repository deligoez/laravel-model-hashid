<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Config;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use RuntimeException;

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

    /** @test */
    public function it_can_get_specific_config_for_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);
        $modelSpecificSeparator = '!';
        $modelSpecificConfig = [ModelA::class => ['separator' => $modelSpecificSeparator]];
        Config::set('hashids.generators', $modelSpecificConfig);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparator = HashIDModelConfig::forModel(new ModelA(), 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($modelSpecificSeparator, $modelSeparator);
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_config_parameters(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::forModel(new ModelA(), 'unknown-config');
    }
}
