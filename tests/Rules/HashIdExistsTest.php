<?php

namespace Deligoez\LaravelModelHashId\Tests\Rules;

use Deligoez\LaravelModelHashId\Rules\HashIdExists;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;

class HashIdExistsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_properly_validate_existing_hashId(): void
    {
        // 1. Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-validate']);
        $hashId = $model->hashId;

        $validator = Validator::make(
            ['test_id' => $hashId],
            ['test_id' => [new HashIdExists(ModelA::class)]],
        );

        // 2. Act 🏋🏻‍
        $passed = $validator->passes();

        // 3. Assert ✅
        $this->assertTrue($passed);
    }

    /** @test */
    public function it_can_properly_validate_missing_hashId(): void
    {
        // 1. Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-validate']);
        $hashId = $model->hashId;
        $model->delete();

        $validator = Validator::make(
            ['test_id' => $hashId],
            ['test_id' => [new HashIdExists(ModelA::class)]],
        );

        // 2. Act 🏋🏻‍
        $passed = $validator->passes();

        // 3. Assert ✅
        $this->assertFalse($passed);
    }

    /** @test */
    public function it_can_return_proper_validation_attribute(): void
    {
        // 1. Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-validate']);
        $hashId = $model->hashId;
        $model->delete();

        $validator = Validator::make(
            ['test_id' => $hashId],
            ['test_id' => [new HashIdExists(ModelA::class)]],
            [],
            ['test_id' => 'TEST_ATTRIBUTE']
        );

        // 2. Act 🏋🏻‍
        $passed = $validator->passes();
        $messages = $validator->messages();

        // 3. Assert ✅
        $this->assertFalse($passed);
        $this->assertStringContainsString('TEST_ATTRIBUTE', $messages->first('test_id'));
    }

    /** @test */
    public function it_can_return_proper_validation_message(): void
    {
        // 1. Arrange 🏗
        ModelA::factory()->count($this->faker->numberBetween(2, 5))->create();
        $model = ModelA::factory()->create(['name' => 'model-that-should-validate']);
        $hashId = $model->hashId;
        $model->delete();

        $validator = Validator::make(
            ['test_id' => $hashId],
            ['test_id' => [new HashIdExists(ModelA::class)]],
            ['test_id.hashIdExists' => 'TEST_MESSAGE']
        );

        // 2. Act 🏋🏻‍
        $passed = $validator->passes();
        $messages = $validator->messages();

        // 3. Assert ✅
        $this->assertFalse($passed);
        $this->assertContains('TEST_MESSAGE', $messages->get('test_id'));
    }
}
