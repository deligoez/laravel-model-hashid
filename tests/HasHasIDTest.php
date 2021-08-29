<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests;

use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Config;
class HasHasIDTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function model_hashID_salt_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();
        $hash = $model->hashID;

        // 2️⃣ Act 🏋🏻‍
        Config::set('hashids.salt', Str::random());

        // 3️⃣ Assert ✅
        $newHash = ModelA::findOrFail($model->getKey())->hashID;
        $this->assertNotEquals($hash, $newHash);
    }

    /** @test */
    public function model_hashID_length_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $randomLength = $this->faker->numberBetween(5, 20);
        Config::set('hashids.length', $randomLength);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->hashID;

        // 3️⃣ Assert ✅
        $this->assertEquals($randomLength ,mb_strlen($hashID));
    }

    /** @test */
    public function model_hashID_alphabet_can_be_defined(): void
    {
        // 1️⃣ Arrange 🏗
        $customAlphabet = 'abcdef1234567890';
        Config::set('hashids.alphabet', $customAlphabet);

        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->hashID;

        // 3️⃣ Assert ✅
        $alphabetAsArray = mb_str_split($customAlphabet);
        foreach (mb_str_split($hashID) as $char) {
            $this->assertContains($char, $alphabetAsArray);
        }
    }

    /** @test */
    public function model_can_encode_its_key(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->encodeHashID();

        // 3️⃣ Assert ✅
        $this->assertEquals($hashID, $model->hashID);
    }

    /** @test */
    public function model_can_encode_any_number(): void
    {
        // 1️⃣ Arrange 🏗
        $randomNumber = $this->faker->randomNumber();

        // 2️⃣ Act 🏋🏻‍
        $hashValue = (new ModelA())->encodeHashID($randomNumber);

        // 3️⃣ Assert ✅
        $this->assertNotEquals($randomNumber, $hashValue);
    }

    /** @test */
    public function model_can_decode_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $key = $model->decodeHashID();

        // 3️⃣ Assert ✅
        $this->assertEquals($key, $model->getKey());
    }

    /** @test */
    public function model_can_decode_any_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $randomNumber = $this->faker->randomNumber();
        $model = new ModelA();
        $hashID = $model->encodeHashID($randomNumber);

        // 2️⃣ Act 🏋🏻‍
        $decodedValue = $model->decodeHashID($hashID);

        // 3️⃣ Assert ✅
        $this->assertEquals($decodedValue, $randomNumber);
    }
}
