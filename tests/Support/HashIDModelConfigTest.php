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
        HashIDModelConfig::set('separator', $genericSeparator);

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
        HashIDModelConfig::set('separator', $genericSeparator);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get('separator', ModelA::class);
        $modelBSeparator = HashIDModelConfig::get('separator', ModelB::class);

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
        HashIDModelConfig::set('separator', $genericSeparator);

        $modelASpecificSeparator = '!';
        HashIDModelConfig::set('separator', $modelASpecificSeparator, ModelA::class);

        $modelBSpecificSeparator = '@';
        HashIDModelConfig::set('separator', $modelBSpecificSeparator, ModelB::class);

        // 2️⃣ Act 🏋🏻‍
        $modelASeparator = HashIDModelConfig::get('separator', ModelA::class);
        $modelBSeparator = HashIDModelConfig::get('separator', ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, $modelASeparator);
        $this->assertEquals($modelBSpecificSeparator, $modelBSeparator);
    }

    /** @test */
    public function it_can_get_specific_config_via_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $genericSeparator = '#';
        HashIDModelConfig::set('separator', $genericSeparator);

        $modelSpecificSeparator = '!';
        HashIDModelConfig::set('separator', $modelSpecificSeparator, ModelA::class);

        // 2️⃣ Act 🏋🏻‍
        $modelSeparatorViaInstance = HashIDModelConfig::get('separator', new ModelA());
        $modelSeparatorViaClassName = HashIDModelConfig::get('separator', ModelA::class);

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
        HashIDModelConfig::set('separator', $genericSeparator);
        HashIDModelConfig::set('length', $genericLength);

        $modelASpecificSeparator = '!';
        $modelASpecificLength = 6;

        $modelBSpecificSeparator = '@';
        $modelBSpecificLength = 10;

        // 2️⃣ Act 🏋🏻‍
        HashIDModelConfig::set('separator', $modelASpecificSeparator, ModelA::class);
        HashIDModelConfig::set('length', $modelASpecificLength, ModelA::class);

        HashIDModelConfig::set('separator', $modelBSpecificSeparator, ModelB::class);
        HashIDModelConfig::set('length', $modelBSpecificLength, ModelB::class);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelASpecificSeparator, HashIDModelConfig::get('separator', ModelA::class));
        $this->assertEquals($modelASpecificLength, HashIDModelConfig::get('length', ModelA::class));

        $this->assertEquals($modelBSpecificSeparator, HashIDModelConfig::get('separator', ModelB::class));
        $this->assertEquals($modelBSpecificLength, HashIDModelConfig::get('length', ModelB::class));
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
