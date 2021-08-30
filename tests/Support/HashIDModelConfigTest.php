<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Config;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelB;
use RuntimeException;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;

class HashIDModelConfigTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);
        $modelA = new ModelA();
        $modelB = new ModelB();

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::forModel($modelA, 'separator');
        $modelBSeparator = HashIDModelConfig::forModel($modelB, 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($genericSeparator, $modelASeparator);
        $this->assertEquals($genericSeparator, $modelBSeparator);
        $this->assertEquals($modelASeparator, $modelBSeparator);
    }

    /** @test */
    public function it_can_get_specific_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);

        $modelASpecificSeparator = '!';
        $modelASpecificConfig = [ModelA::class => ['separator' => $modelASpecificSeparator]];

        $modelBSpecificSeparator = '@';
        $modelBSpecificConfig = [ModelB::class => ['separator' => $modelBSpecificSeparator]];

        Config::set('hashids.generators', array_merge($modelASpecificConfig, $modelBSpecificConfig));

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::forModel(new ModelA(), 'separator');
        $modelBSeparator = HashIDModelConfig::forModel(new ModelB(), 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
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
