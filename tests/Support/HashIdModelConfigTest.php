<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Support;

use Config;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Support\HashIdModelConfig;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIDConfigParameterException;

class HashIdModelConfigTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_set_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '@';
        Config::set(HashIdModelConfig::CONFIG_FILE_NAME.'.' . HashIdModelConfig::SEPARATOR, $genericSeparator);
        $newSeparator = '*';

        // 2️⃣ Act 🏋🏻‍
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $newSeparator);

        // 3️⃣ Assert ✅
        $this->assertEquals($newSeparator, HashIdModelConfig::get(HashIdModelConfig::SEPARATOR));
    }

    /** @test */
    public function it_can_get_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $separator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR);

        // 3️⃣ Assert ✅
        $this->assertEquals($separator, $genericSeparator);
    }

    /** @test */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelA::class);
        $modelBSeparator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelB::class);

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
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $genericSeparator);

        $modelASpecificSeparator = '!';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelASpecificSeparator, ModelA::class);

        $modelBSpecificSeparator = '@';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelBSpecificSeparator, ModelB::class);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelA::class);
        $modelBSeparator = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /** @test */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $genericSeparator);

        $modelSpecificSeparator = '!';
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelSpecificSeparator, ModelA::class);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparatorViaInstance = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, new ModelA());
        $modelSeparatorViaClassName = HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelA::class);

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
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $genericSeparator);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength = 10;

        // 2️⃣ Act 🏋🏻‍
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelASpecificSeparator, ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, $modelASpecificLength, ModelA::class);

        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelBSpecificSeparator, ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, $modelBSpecificLength, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelA::class));
        $this->assertEquals($modelASpecificLength, HashIdModelConfig::get(HashIdModelConfig::LENGTH, ModelA::class));

        $this->assertEquals($modelBSpecificSeparator, HashIdModelConfig::get(HashIdModelConfig::SEPARATOR, ModelB::class));
        $this->assertEquals($modelBSpecificLength, HashIdModelConfig::get(HashIdModelConfig::LENGTH, ModelB::class));
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_parameters(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(UnknownHashIDConfigParameterException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIdModelConfig::isParameterDefined('unknown-config');
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣ Act 🏋🏻‍
        HashIdModelConfig::isModelClassExist('class-that-does-not-exist');
    }
}
