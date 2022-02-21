<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Database\Factories;

use Deligoez\LaravelModelHashId\Tests\Models\ModelD;
use Illuminate\Database\Eloquent\Factories\Factory;

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
