<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Support;

use Hashids\Hashids;
use ReflectionClass;
use RuntimeException;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\HashIdModelConfig;
use Deligoez\LaravelModelHashId\Support\ModelHashIdGenerator;

class ModelHashIdGeneratorTest extends TestCase
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
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, $prefixLength, $model);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals($prefixLength, mb_strlen($prefix));
    }

    /** @test */
    public function prefix_length_will_be_the_length_of_class_name_if_prefix_length_is_under_zero(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();
        $prefixLength = -1;
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, $prefixLength, $model);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $this->assertEquals(mb_strlen($shortClassName), mb_strlen($prefix));
    }

    /** @test */
    public function it_can_set_prefix_length_to_zero_and_prefix_to_empty(): void
    {
        // 1️⃣ Arrange 🏗
        $prefixLength = 0;
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, $prefixLength);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel(ModelA::class);

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
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, $prefixLength);
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $shortClassNameLength = mb_strlen($shortClassName);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals($shortClassNameLength, mb_strlen($prefix));
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        ModelHashIdGenerator::buildPrefixForModel('model-that-not-exist');
    }

    // endregion

    // region prefix_case

    /** @test */
    public function it_can_build_a_lower_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'lower');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('modela', $prefix);
    }

    /** @test */
    public function it_can_build_a_upper_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'upper');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('MODELA', $prefix);
    }

    /** @test */
    public function it_can_build_a_camel_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'camel');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('modelA', $prefix);
    }

    /** @test */
    public function it_can_build_a_snake_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'snake');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('model_a', $prefix);
    }

    /** @test */
    public function it_can_build_a_kebab_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'kebab');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('model-a', $prefix);
    }

    /** @test */
    public function it_can_build_a_title_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'title');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('Modela', $prefix);
    }

    /** @test */
    public function it_can_build_a_studly_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'studly');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('ModelA', $prefix);
    }

    /** @test */
    public function it_can_build_a_plural_studly_case_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 6);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'plural_studly');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIdGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('ModelAS', $prefix);
    }

    // endregion

    /** @test */
    public function it_can_generate_model_hashIDs_using_generic_configuration(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, '@');
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, 5);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'lower');
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 4);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = ModelHashIdGenerator::forModel($model);

        // 3️⃣ Assert ✅
        $modelHash = ModelHashIdGenerator::parseHashIDForModel($hashID);

        $this->assertEquals('mode', $modelHash->prefix);
        $this->assertEquals('@', $modelHash->separator);
        $this->assertEquals($hashID, $model->hashID);
        $this->assertEquals(null, $modelHash->modelClassName);
    }

    /** @test */
    public function it_can_generate_model_hashIDs_with_different_configurations(): void
    {
        // 1️⃣ Arrange 🏗
        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, '_', ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, 5, ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'upper', ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 3, ModelA::class);

        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, '#', ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, 10, ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'lower', ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 4, ModelB::class);

        $modelA = ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashIDA = ModelHashIdGenerator::forModel($modelA);
        $hashIDB = ModelHashIdGenerator::forModel($modelB);

        // 3️⃣ Assert ✅
        $modelHashA = ModelHashIdGenerator::parseHashIDForModel($hashIDA);
        $modelHashB = ModelHashIdGenerator::parseHashIDForModel($hashIDB);

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

        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, $modelSeparator, ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, $modelLength, ModelA::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, $modelPrefixLength, ModelA::class);

        HashIdModelConfig::set(HashIdModelConfig::SEPARATOR, '#', ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, '4', ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_CASE, 'lower', ModelB::class);
        HashIdModelConfig::set(HashIdModelConfig::PREFIX_LENGTH, 4, ModelB::class);

        $model = ModelA::factory()->create();
        $hashID = ModelHashIdGenerator::forModel($model);

        // 2️⃣ Act 🏋🏻‍
        $modelHashID = ModelHashIdGenerator::parseHashIDForModel($hashID);

        // 3️⃣ Assert ✅
        $this->assertEquals($modelLength, mb_strlen($modelHashID->hashIDForKey));
        $this->assertEquals($modelSeparator, $modelHashID->separator);
        $this->assertEquals($modelPrefixLength, mb_strlen($modelHashID->prefix));

        $this->assertEquals($model->hashIDRaw, $modelHashID->hashIDForKey);
        $this->assertEquals($model->hashID, $hashID);
    }

    /** @test */
    public function it_returns_null_if_model_does_not_have_a_key(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $hashIDForModel = ModelHashIdGenerator::forModel($model);

        // 3️⃣ Assert ✅
        $this->assertNull($hashIDForModel);
    }

    /** @test */
    public function it_can_build_a_hashID_generator_from_a_model_instance_or_class_name(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $generatorFromInstance = ModelHashIdGenerator::build($model);
        $generatorFromClassName = ModelHashIdGenerator::build(ModelA::class);

        // 3️⃣ Assert ✅
        $this->assertInstanceOf(Hashids::class, $generatorFromInstance);
        $this->assertInstanceOf(Hashids::class, $generatorFromClassName);
    }

    /** @test */
    public function it_throws_a_runtime_exception_for_class_names_that_does_not_exist_while_building_a_generator(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(RuntimeException::class);

        // 2️⃣ Act 🏋🏻‍
        ModelHashIdGenerator::build('class-name-that-does-not-exist');
    }
}
