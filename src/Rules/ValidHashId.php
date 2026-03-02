<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Rules;

use Closure;
use InvalidArgumentException;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Support\Generator;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHashId implements ValidationRule
{
    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>|null  $model
     */
    public function __construct(
        private readonly ?string $model = null,
    ) {
        if ($this->model !== null && !in_array(HasHashId::class, class_uses_recursive($this->model), true)) {
            throw new InvalidArgumentException("The model [{$this->model}] must use the HasHashId trait.");
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || $value === '') {
            $fail('The :attribute must be a valid hash id.');

            return;
        }

        if ($this->model !== null) {
            if ($this->model::keyFromHashId($value) === null) {
                $fail('The :attribute must be a valid hash id.');
            }

            return;
        }

        if (Generator::parseHashIDForModel($value) === null) {
            $fail('The :attribute must be a valid hash id.');
        }
    }
}
