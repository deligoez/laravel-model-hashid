<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests;

use Str;
use Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Deligoez\LaravelModelHashIDs\Exceptions\CouldNotDecodeHashIDException;

class HasHasIDTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // region Trait Initialization

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
        $this->assertEquals($randomLength, mb_strlen($hashID));
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

    // endregion

    // region Trait Functions

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

    // endregion

    // region Accessors

    /** @test */
    public function model_has_a_hashID_attribute(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $hashID = $model->hashID;
        $decodedID = $model->decodeHashID();
        $key = $model->getKey();

        // 3️⃣ Assert ✅
        $this->assertEquals($key, $decodedID);
        $this->assertEquals($hashID, $model->encodeHashID());
    }

    // endregion

    // region Macros

    /** @test */
    public function it_throws_CouldNotDecodeHashIDException_for_an_invalid_hashID(): void
    {
        // 3️⃣ Assert ✅
        $this->expectException(CouldNotDecodeHashIDException::class);

        // 2️⃣ Act 🏋🏻‍
        ModelA::findByHashID('not-found');
    }

    /** @test */
    public function it_can_find_a_model_by_its_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findByHashID($model->hashID);

        // 3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));
    }

    /** @test */
    public function it_returns_null_if_can_not_find_a_model_with_given_hashID(): void
    {
        // 1️⃣ Arrange 🏗
        $hashID = (new ModelA())->encodeHashID(1);

        // 2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findByHashID($hashID);

        // 3️⃣ Assert ✅
        $this->assertNull($foundModel);
    }

    /** @test */
    public function it_can_find_many_models_by_its_hashIDs(): void
    {
        // 1️⃣ Arrange 🏗
        $models = ModelA::factory()
                        ->count($this->faker->numberBetween(2, 5))
                        ->create();

        $modelHashIDs = $models->pluck('hashID')->toArray();

        // 2️⃣ Act 🏋🏻‍
        $foundModels = ModelA::findManyByHashID($modelHashIDs);

        // 3️⃣ Assert ✅
        $this->assertSame($models->pluck('id')->toArray(), $foundModels->pluck('id')->toArray());
    }

    /** @test */
    public function it_can_find_or_fail_a_model_by_its_hashID(): void
    {
        // 1️⃣.1️⃣ Arrange 🏗
        $model = ModelA::factory()->create();

        // 1️⃣.2️⃣ Act 🏋🏻‍
        $foundModel = ModelA::findOrFailByHashID($model->hashID);

        // 1️⃣.3️⃣ Assert ✅
        $this->assertTrue($model->is($foundModel));

        // 2️⃣.1️⃣ Arrange 🏗
        $model->delete();

        // 2️⃣.3️⃣ Assert ✅
        $this->expectException(ModelNotFoundException::class);

        // 2️⃣.2️⃣ Act 🏋🏻‍
        ModelA::findOrFailByHashID($model->hashID);
    }

    // endregion
}
