<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Str;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Support\HashIdModelConfig;
use Deligoez\LaravelModelHashId\Support\ModelHashIdGenerator;

class HasHasIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // region Trait Initialization

    /** @test */
    public function model_hashId_salt_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hash = $model->hashId;

        // 2️⃣ Act 🏋🏻‍
        HashIdModelConfig::set(HashIdModelConfig::SALT, Str::random());

        // 3️⃣ Assert ✅
        $newHash = ModelA::findOrFail($model->getKey())->hashId;
        $this->assertNotEquals($hash, $newHash);
    }

    /** @test */
    public function model_hashId_length_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $randomLength = $this->faker->numberBetween(5, 20);
        HashIdModelConfig::set(HashIdModelConfig::LENGTH, $randomLength);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3️⃣ Assert ✅
        $length = mb_strlen(HashIdModelConfig::get(HashIdModelConfig::SEPARATOR)) +
            HashIdModelConfig::get(HashIdModelConfig::PREFIX_LENGTH) +
            $randomLength;

        $this->assertEquals($length, mb_strlen($hashId));
    }

    /** @test */
    public function model_hashId_alphabet_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $customAlphabet = 'abcdef1234567890';
        HashIdModelConfig::set(HashIdModelConfig::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3️⃣ Assert ✅
        $modelHashId = ModelHashIdGenerator::parseHashIdForModel($hashId);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashId->hashIDForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    /** @test */
    public function model_hashID_alphabet_can_also_be_defined_from_emojis(): void
    {
        // 1️⃣ Arrange 🏗
        $customAlphabet = '😀😃😄😁😆😅😂🤣🥲☺️😊😇🙂🙃😉😌';
        HashIdModelConfig::set(HashIdModelConfig::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->hashID;

        // 3️⃣ Assert ✅
        $modelHashID = ModelHashIdGenerator::parseHashIDForModel($hashID);

        ray($modelHashID);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashID->hashIDForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    // endregion

    // region Trait Static Functions

    /** @test */
    public function it_can_get_a_model_key_from_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hashID = $model->hashID;

        // 2️⃣ Act 🏋🏻‍
        $key = ModelA::keyFromHashID($hashID);

        // 3️⃣ Assert ✅
        $this->assertEquals($model->getKey(), $key);
    }

    /** @test */
    public function it_returns_null_if_hashID_can_not_parsable(): void
    {
        // 2️⃣ Act 🏋🏻‍
        $key = ModelA::keyFromHashID('non-existing-hash-id');

        // 3️⃣ Assert ✅
        $this->assertNull($key);
    }

    // endregion

    // region Accessors

    /** @test */
    public function model_has_a_hashID_attribute(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->hashID;
        $key = $model->getKey();

        // 3️⃣ Assert ✅
        $this->assertEquals($key, ModelA::keyFromHashID($hashID));
    }

    /** @test */
    public function model_has_a_hashIDRaw_attribute(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashIDRawAttribute = $model->hashIDRaw;

        // 3️⃣ Assert ✅
        $hashIDRaw = ModelHashIdGenerator::parseHashIDForModel($model->hashID)->hashIDForKey;
        $this->assertEquals($hashIDRaw, $hashIDRawAttribute);
    }

    // endregion
}
