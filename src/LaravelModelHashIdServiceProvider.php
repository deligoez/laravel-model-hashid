<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId;

use Spatie\LaravelPackageTools\Package;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdNotMixin;
use Deligoez\LaravelModelHashId\Mixins\FindManyByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrNewByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrFailByHashIdMixin;

class LaravelModelHashIdServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-hashid')
            ->hasConfigFile();
    }

    /**
     * @throws \ReflectionException
     */
    public function bootingPackage(): void
    {
        Builder::mixin(new FindByHashIdMixin());
        Builder::mixin(new FindManyByHashIdMixin());
        Builder::mixin(new FindOrFailByHashIdMixin());
        Builder::mixin(new FindOrNewByHashIdMixin());
        Builder::mixin(new WhereHashIdMixin());
        Builder::mixin(new WhereHashIdNotMixin());
    }
}
