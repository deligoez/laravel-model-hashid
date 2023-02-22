<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Validator;

class HashIdExists extends Exists implements ValidationRule, ValidatorAwareRule
{
    protected Validator $validator;

    /**
     * @param  class-string<Model>  $model
     */
    public function __construct(protected string $model)
    {
        parent::__construct($model, (new $model)->getKeyName());
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! method_exists($this->model, 'keyFromHashId') || ! $id = $this->model::keyFromHashId($value)) {
            $this->fail($attribute,$fail);
            return;
        }

        $validator = ValidatorFacade::make(
            [$attribute => $id],
            [$attribute => (string) $this]
        );

        if ($validator->fails()) {
             $this->fail($attribute, $fail);
        }
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    protected function fail(string $attribute, Closure $fail): void {
        $key = "{$attribute}.hashIdExists";

        if (isset($this->validator->customMessages[$key])) {
            $fail($this->validator->customMessages[$key]);
        } else {
            $fail('validation.exists')->translate([
                'attribute' => $this->validator->getDisplayableAttribute($attribute),
            ]);
        }
    }
}
