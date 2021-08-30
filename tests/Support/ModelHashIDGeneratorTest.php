<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Config;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelB;
use RuntimeException;
use ReflectionClass;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Support\HashIDModelConfig;
use Deligoez\LaravelModelHashIDs\Support\ModelHashIDGenerator;

class ModelHashIDGeneratorTest extends TestCase
{
    use WithFaker;

    // region prefix_length

    /** @test */
    public function it_can_set_prefix_length_for_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLength = $this->faker->numberBetween(1, mb_strlen($shortClassName));
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, $prefixLength, $model);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /** @test */
    public function it_can_set_prefix_length_to_zero_and_prefix_to_empty(): void
    {
        // 1️⃣ Arrange 🏗
        $prefixLength = 0;
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, $prefixLength);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel(ModelA::class);

        // 3️⃣ Assert ✅
        $this->assertEquals('', $prefix);
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /** @test */
    public function prefix_length_will_be_the_short_class_name_length_if_prefix_length_is_more_than_that(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();
        $prefixLength = 10;
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, $prefixLength);
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $shortClassNameLength = mb_strlen($shortClassName);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals($shortClassNameLength, mb_strlen($prefix));
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        ModelHashIDGenerator::buildPrefixForModel('model-that-not-exist');
    }

    // endregion

    // region prefix_case

    /** @test */
    public function it_can_build_a_lower_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'lower');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('modela', $prefix);
    }

    /** @test */
    public function it_can_build_a_upper_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'upper');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('MODELA', $prefix);
    }

    /** @test */
    public function it_can_build_a_camel_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'camel');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('modelA', $prefix);
    }

    /** @test */
    public function it_can_build_a_snake_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'snake');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('model_a', $prefix);
    }

    /** @test */
    public function it_can_build_a_kebab_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'kebab');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('model-a', $prefix);
    }

    /** @test */
    public function it_can_build_a_title_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'title');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('Modela', $prefix);
    }

    /** @test */
    public function it_can_build_a_studly_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'studly');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('ModelA', $prefix);
    }

    /** @test */
    public function it_can_build_a_plural_studly_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_length', 6);
        Config::set('hashids.prefix_case', 'plural_studly');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('ModelAS', $prefix);
    }

    // endregion

    /** @test */
    public function it_can_generate_model_hashIDs_with_different_configurations(): void
    {
        // 1️⃣ Arrange 🏗
        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, '_', ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, 5, ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_CASE, 'upper', ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, 3, ModelA::class);

        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, '#', ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, 10, ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_CASE, 'lower', ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, 4, ModelB::class);

        $modelA = ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashIDA = ModelHashIDGenerator::forModel($modelA);
        $hashIDB = ModelHashIDGenerator::forModel($modelB);

        // 3️⃣ Assert ✅
        $modelHashA = ModelHashIDGenerator::parseHashIDForModel($hashIDA);
        $modelHashB = ModelHashIDGenerator::parseHashIDForModel($hashIDB);

        $this->assertEquals('MOD', $modelHashA->prefix);
        $this->assertEquals('_', $modelHashA->separator);
        $this->assertEquals($hashIDA, $modelA->hashID);
        $this->assertEquals($modelA::class, $modelHashA->modelClassName);

        $this->assertEquals('mode', $modelHashB->prefix);
        $this->assertEquals('#', $modelHashB->separator);
        $this->assertEquals($hashIDB, $modelB->hashID);
        $this->assertEquals($modelB::class, $modelHashB->modelClassName);
    }

    /** @test */
    public function it_can_parse_a_model_hashIDs_into_parts(): void
    {
        // 1️⃣ Arrange 🏗
        $modelSeparator = '_';
        $modelLength = 5;
        $modelPrefixLength = 3;

        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, $modelSeparator, ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, $modelLength, ModelA::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, $modelPrefixLength, ModelA::class);

        HashIDModelConfig::set(HashIDModelConfig::SEPARATOR, '#', ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::LENGTH, '4', ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_CASE, 'lower', ModelB::class);
        HashIDModelConfig::set(HashIDModelConfig::PREFIX_LENGTH, 4, ModelB::class);

        $model = ModelA::factory()->create();
        $hashID = ModelHashIDGenerator::forModel($model);

        // 2️⃣ Act 🏋🏻‍
        $modelHashID = ModelHashIDGenerator::parseHashIDForModel($hashID);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelLength, mb_strlen($modelHashID->hashIDForKey));
        $this->assertEquals($modelSeparator, $modelHashID->separator);
        $this->assertEquals($modelPrefixLength, mb_strlen($modelHashID->prefix));

        $this->assertEquals($model->hashIDRaw, $modelHashID->hashIDForKey);
        $this->assertEquals($model->hashID, $hashID);
    }
}
