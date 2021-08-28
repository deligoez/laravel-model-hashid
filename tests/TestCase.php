<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Deligoez\LaravelModelHashIDs\LaravelModelHashIDsServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Deligoez\\LaravelModelHashIDs\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [LaravelModelHashIDsServiceProvider::class];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
