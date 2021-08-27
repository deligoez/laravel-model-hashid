<?php

namespace Deligoez\LaravelModelHashids;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Deligoez\LaravelModelHashids\Commands\LaravelModelHashidsCommand;

class LaravelModelHashidsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-model-hashids')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-model-hashids_table')
            ->hasCommand(LaravelModelHashidsCommand::class);
    }
}
