<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests;

use Deligoez\LaravelModelHashId\LaravelModelHashIdServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Deligoez\\LaravelModelHashId\\Tests\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [LaravelModelHashIdServiceProvider::class];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
