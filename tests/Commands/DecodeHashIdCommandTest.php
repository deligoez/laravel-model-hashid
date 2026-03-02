<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

it('decodes with explicit model argument', function (): void {
    $model = ModelA::factory()->create();

    $this->artisan('hashid:decode', ['hashid' => $model->hashId, 'model' => ModelA::class])
        ->expectsOutputToContain((string) $model->getKey())
        ->expectsOutputToContain('mod')
        ->assertSuccessful();
});

it('decodes a hash id by scanning registered model generators', function (): void {
    Config::set(ConfigParameters::SALT, 'test-salt', ModelA::class);

    $model = ModelA::factory()->create();

    $this->artisan('hashid:decode', ['hashid' => $model->hashId])
        ->expectsOutputToContain((string) $model->getKey())
        ->assertSuccessful();
});

it('fails for invalid hash id', function (): void {
    $this->artisan('hashid:decode', ['hashid' => 'totally-invalid-hash'])
        ->expectsOutputToContain('Could not decode')
        ->assertFailed();
});

it('fails for non-existent model class', function (): void {
    $this->artisan('hashid:decode', ['hashid' => 'some_hash', 'model' => 'App\\Models\\NonExistent'])
        ->expectsOutputToContain('does not exist')
        ->assertFailed();
});
