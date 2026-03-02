<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Deligoez\LaravelModelHashId\Tests\Models\ModelE;

class ModelEFactory extends Factory
{
    protected $model = ModelE::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
