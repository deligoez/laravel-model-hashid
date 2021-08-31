<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Database\Factories;

use Deligoez\LaravelModelHashIDs\Tests\Models\ModelC;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModelCFactory extends Factory
{
    protected $model = ModelC::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
        ];
    }
}
