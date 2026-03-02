<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Deligoez\LaravelModelHashId\Traits\DecryptsHashIds;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;

function createDecryptingRequest(array $hashIds, array $data): FormRequest
{
    $requestClass = new class() extends FormRequest {
        use DecryptsHashIds;

        public array $hashIds = [];

        public function rules(): array
        {
            return [];
        }

        public function triggerPassedValidation(): void
        {
            $this->passedValidation();
        }
    };

    $request = clone $requestClass;
    $request->hashIds = $hashIds;
    $request->merge($data);

    return $request;
}

it('decodes hash id input to integer key after validation', function (): void {
    $model   = ModelA::factory()->create();
    $request = createDecryptingRequest(
        ['user_id' => ModelA::class],
        ['user_id' => $model->hashId],
    );

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe($model->getKey());
});

it('handles multiple hash id fields', function (): void {
    $modelA  = ModelA::factory()->create();
    $modelB  = ModelB::factory()->create();
    $request = createDecryptingRequest(
        ['user_id' => ModelA::class, 'post_id' => ModelB::class],
        ['user_id' => $modelA->hashId, 'post_id' => $modelB->hashId],
    );

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe($modelA->getKey());
    expect($request->input('post_id'))->toBe($modelB->getKey());
});

it('skips null values', function (): void {
    $request = createDecryptingRequest(
        ['user_id' => ModelA::class],
        ['user_id' => null],
    );

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBeNull();
});

it('skips non-string values', function (): void {
    $request = createDecryptingRequest(
        ['user_id' => ModelA::class],
        ['user_id' => 12345],
    );

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe(12345);
});

it('keeps original value when decode fails', function (): void {
    $request = createDecryptingRequest(
        ['user_id' => ModelA::class],
        ['user_id' => 'totally-invalid-hash'],
    );

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe('totally-invalid-hash');
});

it('works without hashIds property', function (): void {
    $requestClass = new class() extends FormRequest {
        use DecryptsHashIds;

        public function rules(): array
        {
            return [];
        }

        public function triggerPassedValidation(): void
        {
            $this->passedValidation();
        }
    };

    $request = clone $requestClass;
    $request->merge(['user_id' => 'some-value']);

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe('some-value');
});
