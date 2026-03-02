<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Traits;

use Str;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Deligoez\LaravelModelHashId\Support\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

class HasHasIdTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // region Trait Initialization

    /**#[Tests \Deligoez\LaravelModelHashId\Exceptions\UnknownHashIdConfigParameterException
     */
    public function model_hash_id_salt_can_be_defined(): void
    {
        // 1. Arrange 🏗
        /** @var ModelA $model */
        $model = ModelA::factory()->create();
        $hash  = $model->hashId;

        // 2. Act 🏋🏻‍
        Config::set(ConfigParameters::SALT, Str::random());

        // 3. Assert ✅
        $newHash = ModelA::findOrFail($model->getKey())->hashId;
        $this->assertNotEquals($hash, $newHash);
    }

    #[Test]
    public function model_hash_id_length_can_be_defined(): void
    {
        // 1. Arrange 🏗
        $randomLength = $this->faker->numberBetween(5, 20);
        Config::set(ConfigParameters::LENGTH, $randomLength);

        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3. Assert ✅
        $length = mb_strlen(Config::get(ConfigParameters::SEPARATOR)) +
            Config::get(ConfigParameters::PREFIX_LENGTH) +
            $randomLength;

        $this->assertEquals($length, mb_strlen($hashId));
    }

    #[Test]
    public function model_hash_id_alphabet_can_be_defined(): void
    {
        // 1. Arrange 🏗
        $customAlphabet = 'abcdef1234567890';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3. Assert ✅
        $modelHashId = Generator::parseHashIdForModel($hashId);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashId->hashIdForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    #[Test]
    public function model_hash_id_alphabet_can_also_be_defined_from_emojis(): void
    {
        // 1. Arrange 🏗
        $customAlphabet = '😀😃😄😁😆😅😂🤣🥲☺️😊😇🙂🙃😉😌';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = $model->hashId;

        // 3. Assert ✅
        $modelHashID = Generator::parseHashIDForModel($hashId);

        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($modelHashID->hashIdForKey) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    // endregion

    // region Trait Static Functions

    #[Test]
    public function it_can_get_a_model_key_from_hash_id(): void
    {
        // 1. Arrange 🏗
        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;

        // 2. Act 🏋🏻‍
        $key = ModelA::keyFromHashId($hashId);

        // 3. Assert ✅
        $this->assertEquals($model->getKey(), $key);
    }

    #[Test]
    public function it_returns_null_if_hash_id_can_not_parsable(): void
    {
        // 2. Act 🏋🏻‍
        $key = ModelA::keyFromHashId('non-existing-hash-id');

        // 3. Assert ✅
        $this->assertNull($key);
    }

    #[Test]
    public function it_returns_null_if_hash_id_is_valid_format_but_decodes_to_empty(): void
    {
        // 1. Arrange 🏗
        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;
        // Corrupt the last character to create a valid-looking but undecodable hash
        $invalidHashId = mb_substr($hashId, 0, -1).'0';

        // 2. Act 🏋🏻‍
        $key = ModelA::keyFromHashId($invalidHashId);

        // 3. Assert ✅
        $this->assertNull($key);
    }

    #[Test]
    public function it_returns_null_if_hash_id_prefix_does_not_match_model_prefix(): void
    {
        // 1. Arrange 🏗
        Config::set(ConfigParameters::PREFIX, 'a_custom_prefix', ModelA::class);
        Config::set(ConfigParameters::PREFIX, 'b_custom_prefix', ModelB::class);

        ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        $hashId = $modelB->hashId;

        // 2. Act 🏋🏻‍
        $key = ModelA::keyFromHashId($hashId);

        // 3. Assert ✅
        $this->assertNull($key);
    }

    // endregion

    // region Accessors

    #[Test]
    public function model_has_a_hash_id_attribute(): void
    {
        // 1. Arrange 🏗
        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashId = $model->hashId;
        $key    = $model->getKey();

        // 3. Assert ✅
        $this->assertEquals($key, ModelA::keyFromHashId($hashId));
    }

    #[Test]
    public function model_has_a_hash_id_raw_attribute(): void
    {
        // 1. Arrange 🏗
        $model = ModelA::factory()->create();

        // 2. Act 🏋🏻‍
        $hashIdRawAttribute = $model->hashIdRaw;

        // 3. Assert ✅
        $hashIdRaw = Generator::parseHashIDForModel($model->hashId)->hashIdForKey;
        $this->assertEquals($hashIdRaw, $hashIdRawAttribute);
    }

    #[Test]
    public function it_returns_null_if_model_does_not_have_a_key_for_hash_id_raw(): void
    {
        // 1. Arrange 🏗
        /** @var ModelA $model */
        $model = ModelA::factory()->make();

        // 2. Act 🏋🏻‍
        $hashIdRawAttribute = $model->hashIdRaw;

        // 3. Assert ✅
        $this->assertNull($hashIdRawAttribute);
    }

    // endregion
}
