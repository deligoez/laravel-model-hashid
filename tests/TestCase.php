<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests;

use ReflectionClass;
use ReflectionMethod;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
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

    protected function makeMethodPublic(string $name, string|object $classOrObject): ReflectionMethod
    {
        $class = new ReflectionClass($classOrObject);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
