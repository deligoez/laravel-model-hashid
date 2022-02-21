<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Database\Factories;

use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelAFactory extends Factory
{
    protected $model = ModelA::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
