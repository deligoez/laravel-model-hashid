<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Support;

use Hashids\Hashids;
use RuntimeException;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Support\Generator;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

class ModelHashIdGeneratorTest extends TestCase
{
    use WithFaker;

    // region prefix

    /**
     * @test
     */
    public function it_uses_default_prefix_logic_when_override_is_not_defined(): void
    {
        // 1. Arrange 🏗
        $model        = new ModelA();
        $prefixLength = $this->faker->numberBetween(1, mb_strlen(class_basename($model)));
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength, $model);

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /**
     * @test
     */
    public function it_can_use_a_defined_prefix_from_a_model_generator(): void
    {
        // 1. Arrange 🏗
        $modelSeparator = '_';
        $modelPrefix    = 'a_custom_prefix';

        Config::set(ConfigParameters::SEPARATOR, $modelSeparator, ModelA::class);
        Config::set(ConfigParameters::PREFIX, $modelPrefix, ModelA::class);

        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = Generator::forModel($model);

        // 3. Assert ✅
        $modelHash = Generator::parseHashIDForModel($hashId);

        $this->assertEquals($modelPrefix, $modelHash->prefix);
        $this->assertEquals($modelSeparator, $modelHash->separator);
        $this->assertEquals($hashId, $model->hashId);
        $this->assertEquals($model::class, $modelHash->modelClassName);
    }

    // endregion

    // region prefix_length

    /**
     * @test
     */
    public function it_can_set_prefix_length_for_a_model(): void
    {
        // 1. Arrange 🏗
        $model        = new ModelA();
        $prefixLength = $this->faker->numberBetween(1, mb_strlen(class_basename($model)));
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength, $model);

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /**
     * @test
     */
    public function prefix_length_will_be_the_length_of_class_name_if_prefix_length_is_under_zero(): void
    {
        // 1. Arrange 🏗
        $model        = new ModelA();
        $prefixLength = -1;
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength, $model);

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals(mb_strlen(class_basename($model)), mb_strlen($prefix));
    }

    /**
     * @test
     */
    public function it_can_set_prefix_length_to_zero_and_prefix_to_empty(): void
    {
        // 1. Arrange 🏗
        $prefixLength = 0;
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength);

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel(ModelA::class);

        // 3. Assert ✅
        $this->assertEquals('', $prefix);
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /**
     * @test
     */
    public function prefix_length_will_be_the_short_class_name_length_if_prefix_length_is_more_than_that(): void
    {
        // 1. Arrange 🏗
        $model        = new ModelA();
        $prefixLength = 10;
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength);
        $shortClassNameLength = mb_strlen(class_basename($model));

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals($shortClassNameLength, mb_strlen($prefix));
    }

    /**
     * @test
     */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3. Assert ✅
        $this->expectException(RuntimeException::class);

        // 2. Act 🏋🏻‍
        Generator::buildPrefixForModel('model-that-not-exist');
    }

    // endregion

    // region prefix_case

    /**
     * @test
     */
    public function it_can_build_a_lower_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('modela', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_upper_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'upper');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('MODELA', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_camel_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'camel');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('modelA', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_snake_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'snake');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('model_a', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_kebab_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'kebab');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('model-a', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_title_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'title');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('Modela', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_studly_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'studly');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('ModelA', $prefix);
    }

    /**
     * @test
     */
    public function it_can_build_a_plural_studly_case_prefix_from_a_model(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'plural_studly');

        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $prefix = Generator::buildPrefixForModel($model);

        // 3. Assert ✅
        $this->assertEquals('ModelAS', $prefix);
    }

    // endregion

    /**
     * @test
     */
    public function it_can_generate_model_hash_ids_using_generic_configuration(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::SEPARATOR, '@');
        Config::set(ConfigParameters::LENGTH, 5);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower');
        Config::set(ConfigParameters::PREFIX_LENGTH, 4);

        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = Generator::forModel($model);

        // 3. Assert ✅
        $modelHash = Generator::parseHashIDForModel($hashId);

        $this->assertEquals('mode', $modelHash->prefix);
        $this->assertEquals('@', $modelHash->separator);
        $this->assertEquals($hashId, $model->hashId);
        $this->assertEquals(null, $modelHash->modelClassName);
    }

    /**
     * @test
     */
    public function it_can_generate_model_hash_ids_with_different_configurations(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::SEPARATOR, '_', ModelA::class);
        Config::set(ConfigParameters::LENGTH, 5, ModelA::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'upper', ModelA::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 3, ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, '#', ModelB::class);
        Config::set(ConfigParameters::LENGTH, 10, ModelB::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower', ModelB::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 4, ModelB::class);

        $modelA = ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        // 2. Act 🏋🏻‍
        $hashIdA = Generator::forModel($modelA);
        $hashIdB = Generator::forModel($modelB);

        // 3. Assert ✅
        $modelHashA = Generator::parseHashIDForModel($hashIdA);
        $modelHashB = Generator::parseHashIDForModel($hashIdB);

        $this->assertEquals('MOD', $modelHashA->prefix);
        $this->assertEquals('_', $modelHashA->separator);
        $this->assertEquals($hashIdA, $modelA->hashId);
        $this->assertEquals($modelA::class, $modelHashA->modelClassName);

        $this->assertEquals('mode', $modelHashB->prefix);
        $this->assertEquals('#', $modelHashB->separator);
        $this->assertEquals($hashIdB, $modelB->hashId);
        $this->assertEquals($modelB::class, $modelHashB->modelClassName);
    }

    /**
     * @test
     */
    public function it_can_parse_a_model_hash_ids_into_parts(): void
    {
        // 1. Arrange 🏗
        $modelSeparator    = '_';
        $modelLength       = 5;
        $modelPrefixLength = 3;

        Config::set(ConfigParameters::SEPARATOR, $modelSeparator, ModelA::class);
        Config::set(ConfigParameters::LENGTH, $modelLength, ModelA::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, $modelPrefixLength, ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, '#', ModelB::class);
        Config::set(ConfigParameters::LENGTH, '4', ModelB::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower', ModelB::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 4, ModelB::class);

        $model  = ModelA::factory()->create();
        $hashId = Generator::forModel($model);

        // 2. Act 🏋🏻‍
        $modelHashID = Generator::parseHashIDForModel($hashId);

        // 3. Assert ✅
        $this->assertEquals($modelLength, mb_strlen($modelHashID->hashIdForKey));
        $this->assertEquals($modelSeparator, $modelHashID->separator);
        $this->assertEquals($modelPrefixLength, mb_strlen($modelHashID->prefix));

        $this->assertEquals($model->hashIdRaw, $modelHashID->hashIdForKey);
        $this->assertEquals($model->hashId, $hashId);
    }

    /**
     * @test
     */
    public function it_returns_null_if_model_does_not_have_a_key(): void
    {
        // 1. Arrange 🏗
        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $hashIdForModel = Generator::forModel($model);

        // 3. Assert ✅
        $this->assertNull($hashIdForModel);
    }

    /**
     * @test
     */
    public function it_can_build_a_hash_id_generator_from_a_model_instance_or_class_name(): void
    {
        // 1. Arrange 🏗
        $model = new ModelA();

        // 2. Act 🏋🏻‍
        $generatorFromInstance  = Generator::build($model);
        $generatorFromClassName = Generator::build(ModelA::class);

        // 3. Assert ✅
        $this->assertInstanceOf(Hashids::class, $generatorFromInstance);
        $this->assertInstanceOf(Hashids::class, $generatorFromClassName);
    }

    /**
     * @test
     */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist_while_building_a_generator(): void
    {
        // 3. Assert ✅
        $this->expectException(RuntimeException::class);

        // 2. Act 🏋🏻‍
        Generator::build('class-name-that-does-not-exist');
    }
}
