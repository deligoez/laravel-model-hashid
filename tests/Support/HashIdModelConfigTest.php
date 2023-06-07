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
use Deligoez\LaravelModelHashId\Support\ConfigParameters;
use Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException;

class HashIdModelConfigTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_can_set_generic_config_without_model_instance_or_class_name(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '@';
        LaravelConfig::set(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::SEPARATOR, $genericSeparator);
        $newSeparator = '*';

        // 2. Act 🏋🏻‍
        Config::set(ConfigParameters::SEPARATOR, $newSeparator);

        // 3. Assert ✅
        $this->assertEquals($newSeparator, Config::get(ConfigParameters::SEPARATOR));
    }

    /**
     * @test
     */
    public function it_can_get_generic_config_without_model_instance_or_class_name(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '#';
        Config::set(ConfigParameters::SEPARATOR, $genericSeparator);

        // 2. Act 🏋🏻‍
        $separator = Config::get(ConfigParameters::SEPARATOR);

        // 3. Assert ✅
        $this->assertEquals($separator, $genericSeparator);
    }

    /**
     * @test
     */
    public function it_can_get_generic_config_for_different_models(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '#';
        Config::set(ConfigParameters::SEPARATOR, $genericSeparator);

        // 2. Act 🏋🏻‍
        $modelASeparator = Config::get(ConfigParameters::SEPARATOR, ModelA::class);
        $modelBSeparator = Config::get(ConfigParameters::SEPARATOR, ModelB::class);

        // 3. Assert ✅
        $this->assertEquals($genericSeparator, $modelASeparator);
        $this->assertEquals($genericSeparator, $modelBSeparator);
        $this->assertEquals($modelASeparator, $modelBSeparator);
    }

    /**
     * @test
     */
    public function it_can_get_specific_config_for_different_models(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '#';
        Config::set(ConfigParameters::SEPARATOR, $genericSeparator);

        $modelASpecificSeparator = '!';
        Config::set(ConfigParameters::SEPARATOR, $modelASpecificSeparator, ModelA::class);

        $modelBSpecificSeparator = '@';
        Config::set(ConfigParameters::SEPARATOR, $modelBSpecificSeparator, ModelB::class);

        // 2. Act 🏋🏻‍
        $modelASeparator = Config::get(ConfigParameters::SEPARATOR, ModelA::class);
        $modelBSeparator = Config::get(ConfigParameters::SEPARATOR, ModelB::class);

        // 3. Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /**
     * @test
     */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '#';
        Config::set(ConfigParameters::SEPARATOR, $genericSeparator);

        $modelSpecificSeparator = '!';
        Config::set(ConfigParameters::SEPARATOR, $modelSpecificSeparator, ModelA::class);

        // 2. Act 🏋🏻‍
        $modelSeparatorViaInstance  = Config::get(ConfigParameters::SEPARATOR, new ModelA());
        $modelSeparatorViaClassName = Config::get(ConfigParameters::SEPARATOR, ModelA::class);

        // 3. Assert ✅
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaInstance);
        $this->assertEquals($modelSpecificSeparator, $modelSeparatorViaClassName);
        $this->assertEquals($modelSeparatorViaClassName, $modelSeparatorViaInstance);
    }

    /**
     * @test
     */
    public function it_can_set_specific_config_via_model_instance_or_class_name(): void
    {
        // 1. Arrange 🏗
        $genericSeparator = '#';
        $genericLength    = 5;
        Config::set(ConfigParameters::SEPARATOR, $genericSeparator);
        Config::set(ConfigParameters::LENGTH, $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength    = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength    = 10;

        // 2. Act 🏋🏻‍
        Config::set(ConfigParameters::SEPARATOR, $modelASpecificSeparator, ModelA::class);
        Config::set(ConfigParameters::LENGTH, $modelASpecificLength, ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, $modelBSpecificSeparator, ModelB::class);
        Config::set(ConfigParameters::LENGTH, $modelBSpecificLength, ModelB::class);

        // 3. Assert ✅
        $this->assertEquals($modelASpecificSeparator, Config::get(ConfigParameters::SEPARATOR, ModelA::class));
        $this->assertEquals($modelASpecificLength, Config::get(ConfigParameters::LENGTH, ModelA::class));

        $this->assertEquals($modelBSpecificSeparator, Config::get(ConfigParameters::SEPARATOR, ModelB::class));
        $this->assertEquals($modelBSpecificLength, Config::get(ConfigParameters::LENGTH, ModelB::class));
    }

    /**
     * @test
     */
    public function it_throws_a_runtime_exception_for_unknown_parameters(): void
    {
        // 3. Assert ✅
        $this->expectException(UnknownHashIdConfigParameterException::class);

        // 2. Act 🏋🏻‍
        Config::checkIfParameterDefined('unknown-config');
    }

    /**
     * @test
     */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3. Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2. Act 🏋🏻‍
        Config::checkIfModelClassExist('class-that-does-not-exist');
    }
}
