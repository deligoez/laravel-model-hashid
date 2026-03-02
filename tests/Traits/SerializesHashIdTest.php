<?php

declare(strict_types=1);

use Deligoez\LaravelModelHashId\Tests\Models\ModelE;

it('replaces id with hash id in toArray', function (): void {
    $model = ModelE::factory()->create();
    $array = $model->toArray();

    expect($array['id'])->toBe($model->hashId);
});

it('replaces id with hash id in toJson', function (): void {
    $model = ModelE::factory()->create();
    $json  = json_decode($model->toJson(), true);

    expect($json['id'])->toBe($model->hashId);
});

it('does not replace id for unsaved models', function (): void {
    $model = ModelE::factory()->make();
    $array = $model->toArray();

    expect($array)->not->toHaveKey('id');
});

it('respects hidden attributes', function (): void {
    $model = new class() extends ModelE {
        protected $table   = 'model_e_s';
        protected $hidden  = ['name'];
    };

    $model->name = 'hidden';
    $model->save();
    $model->refresh();

    $array = $model->toArray();

    expect($array)->not->toHaveKey('name');
    expect($array['id'])->toBe($model->hashId);
});

it('includes all other model attributes', function (): void {
    $model = ModelE::factory()->create(['name' => 'John Doe']);
    $array = $model->toArray();

    expect($array)
        ->toHaveKey('id', $model->hashId)
        ->toHaveKey('name', 'John Doe')
        ->toHaveKey('created_at')
        ->toHaveKey('updated_at');
});
