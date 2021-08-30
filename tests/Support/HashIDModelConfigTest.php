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

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get(model: ModelA::class, config: 'separator');
        $modelBSeparator = HashIDModelConfig::get(model: ModelB::class, config: 'separator');

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
        HashIDModelConfig::set(ModelA::class, 'separator', $modelASpecificSeparator);

        $modelBSpecificSeparator = '@';
        HashIDModelConfig::set(ModelB::class, 'separator', $modelBSpecificSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get(model: new ModelA(), config: 'separator');
        $modelBSeparator = HashIDModelConfig::get(model: new ModelB(), config: 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_config_parameters_while_retrieving(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::get(model: ModelA::class, config: 'unknown-config');
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_config_parameters_while_setting(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set(model: ModelB::class, config: 'unknown-config', value: 'any-value');
    }

    /** @test */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);

        $modelSpecificSeparator = '!';
        $modelSpecificConfig = [ModelA::class => ['separator' => $modelSpecificSeparator]];

        Config::set('hashids.generators', $modelSpecificConfig);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparatorViaInstance = HashIDModelConfig::get(model: new ModelA(), config: 'separator');
        $modelSeparatorViaClassName = HashIDModelConfig::get(model: ModelA::class, config: 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaInstance);
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaClassName);
        $this->assertEquals($modelSeparatorViaClassName, $modelSeparatorViaInstance);
    }

    /** @test */
    public function it_throws_a_runtime_exception_if_FQCN_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::get(model: 'class-that-not-exists', config: 'separator');
    }
}
