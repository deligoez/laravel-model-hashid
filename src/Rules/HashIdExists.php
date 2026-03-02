<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Rules;

use Closure;
use InvalidArgumentException;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Contracts\Validation\ValidationRule;

class HashIdExists implements ValidationRule
{
    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $model
     */
    public function __construct(
        private readonly string $model,
    ) {
        if (!in_array(HasHashId::class, class_uses_recursive($this->model), true)) {
            throw new InvalidArgumentException("The model [{$this->model}] must use the HasHashId trait.");
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || $value === '') {
            $fail('The selected :attribute is invalid.');

            return;
        }

        $key = $this->model::keyFromHashId($value);

        if ($key === null) {
            $fail('The selected :attribute is invalid.');

            return;
        }

        $instance = new $this->model();
        $exists   = $this->model::where($instance->getKeyName(), $key)->exists();

        if (!$exists) {
            $fail('The selected :attribute is invalid.');
        }
    }
}
