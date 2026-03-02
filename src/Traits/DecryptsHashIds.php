<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
trait DecryptsHashIds
{
    /**
     * Handle a passed validation attempt.
     * Decodes hash ID inputs to their integer keys after validation passes.
     */
    protected function passedValidation(): void
    {
        if (!property_exists($this, 'hashIds') || !is_array($this->hashIds)) {
            return;
        }

        foreach ($this->hashIds as $field => $modelClass) {
            $value = $this->input($field);

            if ($value === null || !is_string($value)) {
                continue;
            }

            $key = $modelClass::keyFromHashId($value);

            if ($key !== null) {
                $this->merge([$field => $key]);
            }
        }
    }
}
