<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Config;
use RuntimeException;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelB;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;

class HashIDModelConfigTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_set_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '@';
        Config::set(HashIDModelConfig::CONFIG_FILE_NAME.'.' . HashIDModelConfig::SEPARATOR, $genericSeparator);
        $newSeparator = '*';

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $newSeparator);

        // 3️⃣ Assert ✅
        $this->assertEquals($newSeparator, HashIDModelConfig::get(HashIDModelConfig::SEPARATOR));
    }

    /** @test */
    public function it_can_get_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $separator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR);

        // 3️⃣ Assert ✅
        $this->assertEquals($separator, $genericSeparator);
    }

    /** @test */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelA::class);
        $modelBSeparator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelB::class);

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
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $genericSeparator);

        $modelASpecificSeparator = '!';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelASpecificSeparator, ModelA::class);

        $modelBSpecificSeparator = '@';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelBSpecificSeparator, ModelB::class);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelA::class);
        $modelBSeparator = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /** @test */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $genericSeparator);

        $modelSpecificSeparator = '!';
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelSpecificSeparator, ModelA::class);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparatorViaInstance = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, new ModelA());
        $modelSeparatorViaClassName = HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelA::class);

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
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $genericSeparator);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength = 10;

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelASpecificSeparator, ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, $modelASpecificLength, ModelA::class);

        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelBSpecificSeparator, ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, $modelBSpecificLength, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelA::class));
        $this->assertEquals($modelASpecificLength, HashIDModelConfig::get(HashIDModelConfig::LENGTH, ModelA::class));

        $this->assertEquals($modelBSpecificSeparator, HashIDModelConfig::get(HashIDModelConfig::SEPARATOR, ModelB::class));
        $this->assertEquals($modelBSpecificLength, HashIDModelConfig::get(HashIDModelConfig::LENGTH, ModelB::class));
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_parameters(): void
    {
        // 1️⃣ Arrange 🏗
        $method = $this->makeMethodPublic('isParameterDefined', HashIDModelConfig::class);

        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        $method->invokeArgs(null, ['unknown-config']);
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 1️⃣ Arrange 🏗
        $method = $this->makeMethodPublic('isModelClassExist', HashIDModelConfig::class);

        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        $method->invokeArgs(null, ['class-that-does-not-exist']);
    }
}
