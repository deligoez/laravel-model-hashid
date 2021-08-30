<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Database\Factories;

use Deligoez\LaravelModelHashIDs\Tests\Models\ModelB;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelBFactory extends Factory
{
    protected string $model = ModelB::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
        ];
    }
}
