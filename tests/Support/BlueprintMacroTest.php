<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('registers hashId macro on Blueprint', function (): void {
    expect(Blueprint::hasMacro('hashId'))->toBeTrue();
});

it('creates a nullable string column with default name', function (): void {
    Schema::create('test_blueprint_macro', function (Blueprint $table): void {
        $table->id();
        $column = $table->hashId();
        $table->timestamps();

        expect($column->get('name'))->toBe('hash_id');
        expect($column->get('type'))->toBe('string');
        expect($column->get('nullable'))->toBeTrue();
    });

    expect(Schema::hasColumn('test_blueprint_macro', 'hash_id'))->toBeTrue();

    Schema::drop('test_blueprint_macro');
});

it('creates a column with custom name', function (): void {
    Schema::create('test_blueprint_custom', function (Blueprint $table): void {
        $table->id();
        $column = $table->hashId('custom_hash');
        $table->timestamps();

        expect($column->get('name'))->toBe('custom_hash');
    });

    expect(Schema::hasColumn('test_blueprint_custom', 'custom_hash'))->toBeTrue();

    Schema::drop('test_blueprint_custom');
});

it('creates a unique index on the column', function (): void {
    Schema::create('test_blueprint_unique', function (Blueprint $table): void {
        $table->id();
        $column = $table->hashId();
        $table->timestamps();

        expect($column->get('unique'))->toBeTrue();
    });

    Schema::drop('test_blueprint_unique');
});
