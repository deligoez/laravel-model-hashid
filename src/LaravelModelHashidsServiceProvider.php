<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs;

use Spatie\LaravelPackageTools\Package;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\WhereHashIDNotMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindManyByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrNewByHashIDMixin;
use Deligoez\LaravelModelHashIDs\Mixins\FindOrFailByHashIDMixin;

class LaravelModelHashIDsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-hashids')
            ->hasConfigFile('hashids');
    }

    /**
     * @throws \ReflectionException
     */
    public function bootingPackage(): void
    {
        Builder::mixin(new FindByHashIDMixin());
        Builder::mixin(new FindManyByHashIDMixin());
        Builder::mixin(new FindOrFailByHashIDMixin());
        Builder::mixin(new FindOrNewByHashIDMixin());
        Builder::mixin(new WhereHashIDMixin());
        Builder::mixin(new WhereHashIDNotMixin());
    }
}
