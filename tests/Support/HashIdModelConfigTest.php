<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Support;

use Config as LaravelConfig;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

class HashIdModelConfigTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_set_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '@';
        LaravelConfig::set(Config::CONFIG_FILE_NAME.'.' . Config::SEPARATOR, $genericSeparator);
        $newSeparator = '*';

        // 2️⃣ Act 🏋🏻‍
        Config::set(Config::SEPARATOR, $newSeparator);

        // 3️⃣ Assert ✅
        $this->assertEquals($newSeparator, Config::get(Config::SEPARATOR));
    }

    /** @test */
    public function it_can_get_generic_config_without_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set(Config::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $separator = Config::get(Config::SEPARATOR);

        // 3️⃣ Assert ✅
        $this->assertEquals($separator, $genericSeparator);
    }

    /** @test */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set(Config::SEPARATOR, $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = Config::get(Config::SEPARATOR, ModelA::class);
        $modelBSeparator = Config::get(Config::SEPARATOR, ModelB::class);

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
        Config::set(Config::SEPARATOR, $genericSeparator);

        $modelASpecificSeparator = '!';
        Config::set(Config::SEPARATOR, $modelASpecificSeparator, ModelA::class);

        $modelBSpecificSeparator = '@';
        Config::set(Config::SEPARATOR, $modelBSpecificSeparator, ModelB::class);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = Config::get(Config::SEPARATOR, ModelA::class);
        $modelBSeparator = Config::get(Config::SEPARATOR, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /** @test */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        Config::set(Config::SEPARATOR, $genericSeparator);

        $modelSpecificSeparator = '!';
        Config::set(Config::SEPARATOR, $modelSpecificSeparator, ModelA::class);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparatorViaInstance = Config::get(Config::SEPARATOR, new ModelA());
        $modelSeparatorViaClassName = Config::get(Config::SEPARATOR, ModelA::class);

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
        Config::set(Config::SEPARATOR, $genericSeparator);
        Config::set(Config::LENGTH, $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength = 10;

        // 2️⃣ Act 🏋🏻‍
        Config::set(Config::SEPARATOR, $modelASpecificSeparator, ModelA::class);
        Config::set(Config::LENGTH, $modelASpecificLength, ModelA::class);

        Config::set(Config::SEPARATOR, $modelBSpecificSeparator, ModelB::class);
        Config::set(Config::LENGTH, $modelBSpecificLength, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, Config::get(Config::SEPARATOR, ModelA::class));
        $this->assertEquals($modelASpecificLength, Config::get(Config::LENGTH, ModelA::class));

        $this->assertEquals($modelBSpecificSeparator, Config::get(Config::SEPARATOR, ModelB::class));
        $this->assertEquals($modelBSpecificLength, Config::get(Config::LENGTH, ModelB::class));
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_unknown_parameters(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(UnknownHashIdConfigParameterException::class);

        // 2️⃣ Act 🏋🏻‍
        Config::isParameterDefined('unknown-config');
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣ Act 🏋🏻‍
        Config::isModelClassExist('class-that-does-not-exist');
    }
}
