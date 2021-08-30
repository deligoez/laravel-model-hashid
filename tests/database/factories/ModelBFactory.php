<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelB;

class ModelBFactory extends Factory
{
    protected $model = ModelB::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
        ];
    }
}
