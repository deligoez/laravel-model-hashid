<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindByHashIdMixin;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;
use Deligoez\LaravelModelHashId\Mixins\FindOrByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\WhereHashIdNotMixin;
use Deligoez\LaravelModelHashId\Commands\DecodeHashIdCommand;
use Deligoez\LaravelModelHashId\Commands\EncodeHashIdCommand;
use Deligoez\LaravelModelHashId\Mixins\FindManyByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrNewByHashIdMixin;
use Deligoez\LaravelModelHashId\Mixins\FindOrFailByHashIdMixin;

class LaravelModelHashIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/model-hashid.php' => config_path('model-hashid.php'),
            ], 'config');

            $this->commands([
                EncodeHashIdCommand::class,
                DecodeHashIdCommand::class,
            ]);
        }

        $this->bootMixins();
        $this->bootBlueprintMacros();
        $this->bootBladeDirectives();
    }

    /**
     * Register any application services.
     */
    #[\Override]
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

    /**
     * Boots the Blueprint macros.
     */
    protected function bootBlueprintMacros(): void
    {
        Blueprint::macro('hashId', function (?string $column = null): ColumnDefinition {
            $resolvedColumn = $column ?? Config::get(ConfigParameters::CONFIG_FILE_NAME.'.'.ConfigParameters::DATABASE_COLUMN, 'hash_id');

            /* @var Blueprint $this */
            return $this->string($resolvedColumn)->nullable()->unique();
        });
    }

    /**
     * Boots the Blade directives.
     */
    protected function bootBladeDirectives(): void
    {
        Blade::directive('hashid', function (string $expression): string {
            return "<?php echo e({$expression}->hashId); ?>";
        });
    }
}
