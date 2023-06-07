<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Database\Eloquent\Builder;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdNotMixin;
use Deligoez\LaravelModelHashId\Mixins\FindManyByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrNewByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrFailByHashIdMixin;

class LaravelModelHashIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     *
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/model-hashid.php' => config_path('model-hashid.php'),
            ], 'config');
        }

        $this->bootMixins();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/model-hashid.php', 'model-hashid');
    }

    /**
     * Boots the Query Builder Mixins.
     *
     * @throws \ReflectionException
     */
    protected function bootMixins(): void
    {
        Builder::mixin(new FindByHashIdMixin());
        Builder::mixin(new FindManyByHashIdMixin());
        Builder::mixin(new FindOrFailByHashIdMixin());
        Builder::mixin(new FindOrByHashIdMixin());
        Builder::mixin(new FindOrNewByHashIdMixin());
        Builder::mixin(new WhereHashIdMixin());
        Builder::mixin(new WhereHashIdNotMixin());
    }
}
