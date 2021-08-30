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
    public function it_can_set_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '@';
        Config::set('hashids.separator', $genericSeparator);
        $newSeparator = '*';

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set('separator', $newSeparator);

        // 3️⃣ Assert ✅
        $this->assertEquals($newSeparator, HashIDModelConfig::get('separator'));
    }

    /** @test */
    public function it_can_get_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $separator = HashIDModelConfig::get('separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($separator, $genericSeparator);
    }

    /** @test */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set('hashids.separator', $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get(model: ModelA::class, parameter: 'separator');
        $modelBSeparator = HashIDModelConfig::get(model: ModelB::class, parameter: 'separator');

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
        $modelASeparator = HashIDModelConfig::get(model: new ModelA(), parameter: 'separator');
        $modelBSeparator = HashIDModelConfig::get(model: new ModelB(), parameter: 'separator');

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
        HashIDModelConfig::get(model: ModelA::class, parameter: 'unknown-config');
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
        $modelSeparatorViaInstance = HashIDModelConfig::get(model: new ModelA(), parameter: 'separator');
        $modelSeparatorViaClassName = HashIDModelConfig::get(model: ModelA::class, parameter: 'separator');

        // 3️⃣ Assert ✅
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaInstance);
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaClassName);
        $this->assertEquals($modelSeparatorViaClassName, $modelSeparatorViaInstance);
    }

    /** @test */
    public function it_can_set_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        $genericLength = 5;
        Config::set('hashids.separator', $genericSeparator);
        Config::set('hashids.length', $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength = 10;

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set(ModelA::class, 'separator', $modelASpecificSeparator);
        HashIDModelConfig::set(ModelA::class, 'length', $modelASpecificLength);

        HashIDModelConfig::set(ModelB::class, 'separator', $modelBSpecificSeparator);
        HashIDModelConfig::set(ModelB::class, 'length', $modelBSpecificLength);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, HashIDModelConfig::get(ModelA::class, 'separator'));
        $this->assertEquals($modelASpecificLength, HashIDModelConfig::get(ModelA::class, 'length'));

        $this->assertEquals($modelBSpecificSeparator, HashIDModelConfig::get(ModelB::class, 'separator'));
        $this->assertEquals($modelBSpecificLength, HashIDModelConfig::get(ModelB::class, 'length'));
    }

    /** @test */
    public function it_throws_a_runtime_exception_if_FQCN_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::get(model: 'class-that-not-exists', parameter: 'separator');
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_config_parameters_while_setting(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set(model: ModelB::class, parameter: 'unknown-config', value: 'any-value');
    }
}
