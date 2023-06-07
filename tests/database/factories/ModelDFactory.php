<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Deligoez\LaravelModelHashId\Tests\Models\ModelD;

class ModelDFactory extends Factory
{
    protected $model = ModelD::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
        ];
    }
}
