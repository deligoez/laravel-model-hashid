<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Traits\DecryptsHashIds;

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

    $request          = clone $requestClass;
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

it('makes decoded values available via validated()', function (): void {
    $model = ModelA::factory()->create();

    $requestClass = new class() extends FormRequest {
        use DecryptsHashIds;

        public array $hashIds = [];

        public function rules(): array
        {
            return [
                'user_id' => ['required', 'string'],
            ];
        }

        public function triggerPassedValidation(): void
        {
            $this->passedValidation();
        }
    };

    $request          = clone $requestClass;
    $request->hashIds = ['user_id' => ModelA::class];
    $request->merge(['user_id' => $model->hashId]);

    $validator = Validator::make($request->all(), $request->rules());
    $request->setValidator($validator);
    $validator->validate();

    $request->triggerPassedValidation();

    expect($request->input('user_id'))->toBe($model->getKey());
    expect($request->validated('user_id'))->toBe($model->getKey());
});

it('makes multiple decoded values available via validated()', function (): void {
    $modelA = ModelA::factory()->create();
    $modelB = ModelB::factory()->create();

    $requestClass = new class() extends FormRequest {
        use DecryptsHashIds;

        public array $hashIds = [];

        public function rules(): array
        {
            return [
                'user_id' => ['required', 'string'],
                'post_id' => ['required', 'string'],
            ];
        }

        public function triggerPassedValidation(): void
        {
            $this->passedValidation();
        }
    };

    $request          = clone $requestClass;
    $request->hashIds = ['user_id' => ModelA::class, 'post_id' => ModelB::class];
    $request->merge(['user_id' => $modelA->hashId, 'post_id' => $modelB->hashId]);

    $validator = Validator::make($request->all(), $request->rules());
    $request->setValidator($validator);
    $validator->validate();

    $request->triggerPassedValidation();

    expect($request->validated('user_id'))->toBe($modelA->getKey());
    expect($request->validated('post_id'))->toBe($modelB->getKey());
});

it('returns all decoded values when validated() is called without arguments', function (): void {
    $model = ModelA::factory()->create();

    $requestClass = new class() extends FormRequest {
        use DecryptsHashIds;

        public array $hashIds = [];

        public function rules(): array
        {
            return [
                'user_id' => ['required', 'string'],
                'name'    => ['required', 'string'],
            ];
        }

        public function triggerPassedValidation(): void
        {
            $this->passedValidation();
        }
    };

    $request          = clone $requestClass;
    $request->hashIds = ['user_id' => ModelA::class];
    $request->merge(['user_id' => $model->hashId, 'name' => 'John']);

    $validator = Validator::make($request->all(), $request->rules());
    $request->setValidator($validator);
    $validator->validate();

    $request->triggerPassedValidation();

    $validated = $request->validated();
    expect($validated['user_id'])->toBe($model->getKey());
    expect($validated['name'])->toBe('John');
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
