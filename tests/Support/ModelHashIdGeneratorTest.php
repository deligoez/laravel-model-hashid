<?php

declare(strict_types=1);

use Hashids\Hashids;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Support\Generator;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

describe('prefix', function (): void {
    it('uses default prefix logic when override is not defined', function (): void {
        $model        = new ModelA();
        $prefixLength = fake()->numberBetween(1, mb_strlen(class_basename($model)));
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength, $model);

        $prefix = Generator::buildPrefixForModel($model);

        expect(mb_strlen($prefix))->toEqual($prefixLength);
    });

    it('can use a defined prefix from a model generator', function (): void {
        Config::set(ConfigParameters::SEPARATOR, '_', ModelA::class);
        Config::set(ConfigParameters::PREFIX, 'a_custom_prefix', ModelA::class);

        $model  = ModelA::factory()->create();
        $hashId = Generator::forModel($model);

        $modelHash = Generator::parseHashIDForModel($hashId);

        expect($modelHash->prefix)->toEqual('a_custom_prefix')
            ->and($modelHash->separator)->toEqual('_')
            ->and($model->hashId)->toEqual($hashId)
            ->and($modelHash->modelClassName)->toEqual($model::class);
    });
});

describe('prefix_length', function (): void {
    it('can set prefix length for a model', function (): void {
        $model        = new ModelA();
        $prefixLength = fake()->numberBetween(1, mb_strlen(class_basename($model)));
        Config::set(ConfigParameters::PREFIX_LENGTH, $prefixLength, $model);

        $prefix = Generator::buildPrefixForModel($model);

        expect(mb_strlen($prefix))->toEqual($prefixLength);
    });

    it('uses full class name length when prefix length is under zero', function (): void {
        $model = new ModelA();
        Config::set(ConfigParameters::PREFIX_LENGTH, -1, $model);

        $prefix = Generator::buildPrefixForModel($model);

        expect(mb_strlen($prefix))->toEqual(mb_strlen(class_basename($model)));
    });

    it('can set prefix length to zero and prefix to empty', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 0);

        $prefix = Generator::buildPrefixForModel(ModelA::class);

        expect($prefix)->toEqual('')
            ->and(mb_strlen($prefix))->toEqual(0);
    });

    it('caps prefix length at short class name length', function (): void {
        $model = new ModelA();
        Config::set(ConfigParameters::PREFIX_LENGTH, 10);

        $prefix = Generator::buildPrefixForModel($model);

        expect(mb_strlen($prefix))->toEqual(mb_strlen(class_basename($model)));
    });

    it('throws a runtime exception for class names that does not exist', function (): void {
        expect(fn () => Generator::buildPrefixForModel('model-that-not-exist'))
            ->toThrow(RuntimeException::class);
    });
});

describe('prefix_case', function (): void {
    it('can build a lower case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('modela');
    });

    it('can build an upper case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'upper');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('MODELA');
    });

    it('can build a camel case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'camel');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('modelA');
    });

    it('can build a snake case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'snake');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('model_a');
    });

    it('can build a kebab case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'kebab');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('model-a');
    });

    it('can build a title case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'title');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('Modela');
    });

    it('can build a studly case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'studly');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('ModelA');
    });

    it('can build a plural studly case prefix', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, 6);
        Config::set(ConfigParameters::PREFIX_CASE, 'plural_studly');

        expect(Generator::buildPrefixForModel(new ModelA()))->toEqual('ModelAS');
    });
});

describe('generator', function (): void {
    it('can generate model hash ids using generic configuration', function (): void {
        Config::set(ConfigParameters::SEPARATOR, '@');
        Config::set(ConfigParameters::LENGTH, 5);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower');
        Config::set(ConfigParameters::PREFIX_LENGTH, 4);

        $model  = ModelA::factory()->create();
        $hashId = Generator::forModel($model);

        $modelHash = Generator::parseHashIDForModel($hashId);

        expect($modelHash->prefix)->toEqual('mode')
            ->and($modelHash->separator)->toEqual('@')
            ->and($model->hashId)->toEqual($hashId)
            ->and($modelHash->modelClassName)->toBeNull();
    });

    it('can generate model hash ids with different configurations', function (): void {
        Config::set(ConfigParameters::SEPARATOR, '_', ModelA::class);
        Config::set(ConfigParameters::LENGTH, 5, ModelA::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'upper', ModelA::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 3, ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, '#', ModelB::class);
        Config::set(ConfigParameters::LENGTH, 10, ModelB::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower', ModelB::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 4, ModelB::class);

        $modelA = ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        $hashIdA = Generator::forModel($modelA);
        $hashIdB = Generator::forModel($modelB);

        $modelHashA = Generator::parseHashIDForModel($hashIdA);
        $modelHashB = Generator::parseHashIDForModel($hashIdB);

        expect($modelHashA->prefix)->toEqual('MOD')
            ->and($modelHashA->separator)->toEqual('_')
            ->and($modelA->hashId)->toEqual($hashIdA)
            ->and($modelHashA->modelClassName)->toEqual($modelA::class)
            ->and($modelHashB->prefix)->toEqual('mode')
            ->and($modelHashB->separator)->toEqual('#')
            ->and($modelB->hashId)->toEqual($hashIdB)
            ->and($modelHashB->modelClassName)->toEqual($modelB::class);
    });

    it('can parse model hash ids into parts', function (): void {
        Config::set(ConfigParameters::SEPARATOR, '_', ModelA::class);
        Config::set(ConfigParameters::LENGTH, 5, ModelA::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 3, ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, '#', ModelB::class);
        Config::set(ConfigParameters::LENGTH, '4', ModelB::class);
        Config::set(ConfigParameters::PREFIX_CASE, 'lower', ModelB::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 4, ModelB::class);

        $model  = ModelA::factory()->create();
        $hashId = Generator::forModel($model);

        $modelHashID = Generator::parseHashIDForModel($hashId);

        expect(mb_strlen($modelHashID->hashIdForKey))->toEqual(5)
            ->and($modelHashID->separator)->toEqual('_')
            ->and(mb_strlen($modelHashID->prefix))->toEqual(3)
            ->and($modelHashID->hashIdForKey)->toEqual($model->hashIdRaw)
            ->and($hashId)->toEqual($model->hashId);
    });

    it('returns null if model does not have a key', function (): void {
        expect(Generator::forModel(new ModelA()))->toBeNull();
    });

    it('returns null when parsing with generic prefix length -1 and no class name', function (): void {
        Config::set(ConfigParameters::PREFIX_LENGTH, -1);

        expect(Generator::parseHashIDForModel('some_hashid'))->toBeNull();
    });

    it('can build a hash id generator from a model instance or class name', function (): void {
        expect(Generator::build(new ModelA()))->toBeInstanceOf(Hashids::class)
            ->and(Generator::build(ModelA::class))->toBeInstanceOf(Hashids::class);
    });

    it('throws a runtime exception for invalid class names while building', function (): void {
        expect(fn () => Generator::build('class-name-that-does-not-exist'))
            ->toThrow(RuntimeException::class);
    });

    it('can have both empty prefix and separator', function (): void {
        Config::set(ConfigParameters::SEPARATOR, '', ModelA::class);
        Config::set(ConfigParameters::LENGTH, 5, ModelA::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 0, ModelA::class);
        Config::set(ConfigParameters::SALT, 'salt-for-model-a', ModelA::class);

        Config::set(ConfigParameters::SEPARATOR, '', ModelB::class);
        Config::set(ConfigParameters::LENGTH, 5, ModelB::class);
        Config::set(ConfigParameters::PREFIX_LENGTH, 0, ModelB::class);
        Config::set(ConfigParameters::SALT, 'salt-for-model-b', ModelB::class);

        $modelA = ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        $hashIdA = Generator::forModel($modelA);
        $hashIdB = Generator::forModel($modelB);

        $modelHashA = Generator::parseHashIDForModel($hashIdA, ModelA::class);
        $modelHashB = Generator::parseHashIDForModel($hashIdB, ModelB::class);

        expect($modelHashA->prefix)->toEqual('')
            ->and($modelHashA->separator)->toEqual('')
            ->and($modelA->hashId)->toEqual($hashIdA)
            ->and($modelHashA->modelClassName)->toEqual(ModelA::class)
            ->and($modelHashB->prefix)->toEqual('')
            ->and($modelHashB->separator)->toEqual('')
            ->and($modelB->hashId)->toEqual($hashIdB)
            ->and($modelHashB->modelClassName)->toEqual(ModelB::class);
    });
});
