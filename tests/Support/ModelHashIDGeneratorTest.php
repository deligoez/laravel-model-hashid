<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Deligoez\LaravelModelHashIDs\Support\ModelHashIDGenerator;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Config;
use Illuminate\Foundation\Testing\WithFaker;
use ReflectionClass;

class ModelHashIDGeneratorTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_set_prefix_lenght_for_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();
        $shortClassName = (new ReflectionClass($model))->getShortName();
        $prefixLenght = $this->faker->numberBetween(1, mb_strlen($shortClassName));
        Config::set('hashids.prefix_lenght', $prefixLenght);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals($prefixLenght, mb_strlen($prefix));
    }

    /** @test */
    public function it_can_set_prefix_lenght_to_zero_and_prefix_to_empty(): void
    {
        // 1️⃣ Arrange 🏗
        $model = new ModelA();
        $prefixLenght = 0;
        Config::set('hashids.prefix_lenght', $prefixLenght);

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('', $prefix);
        $this->assertEquals($prefixLenght, mb_strlen($prefix));
    }

    // TODO: Buyukse, kucukse

    /** @test */
    public function it_can_build_a_lowercase_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_lenght', 3);
        Config::set('hashids.prefix_case', 'lower');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('mod', $prefix);
    }
}