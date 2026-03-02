<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Tests\Models\ModelC;
use Deligoez\LaravelModelHashId\Tests\Models\ModelD;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

it('saves hash id after a model is created', function (): void {
    $model = ModelC::factory()->create();

    expect(ModelC::find($model->id)->hashId)->toEqual($model->hash_id);
});

it('saves hash id to a custom column after a model is created', function (): void {
    Config::set(ConfigParameters::DATABASE_COLUMN, 'hash', ModelD::class);

    $model = ModelD::factory()->create();

    expect(ModelD::find($model->id)->hashId)->toEqual($model->hash);
});
