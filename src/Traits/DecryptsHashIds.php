<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Traits;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
trait DecryptsHashIds
{
    /** @var array<string, int> */
    private array $decryptedHashIds = [];

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
            if ($value === null) {
                continue;
            }
            if (!is_string($value)) {
                continue;
            }

            $key = $modelClass::keyFromHashId($value);

            if ($key !== null) {
                $this->merge([$field => $key]);
                $this->decryptedHashIds[$field] = $key;
            }
        }
    }

    /**
     * Get the validated data with decrypted hash IDs.
     */
    public function validated($key = null, $default = null): mixed
    {
        $validated = $this->validator->validated();

        foreach ($this->decryptedHashIds as $field => $decodedKey) {
            if (array_key_exists($field, $validated)) {
                $validated[$field] = $decodedKey;
            }
        }

        return data_get($validated, $key, $default);
    }
}
