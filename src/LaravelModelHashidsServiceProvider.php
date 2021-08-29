<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelModelHashIDsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-hashids')
            ->hasConfigFile('hashids');
    }
}
