<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Support\Generator;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;
use Deligoez\LaravelModelHashId\Tests\Models\ModelB;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

describe('initialization', function (): void {
    it('can define model hash id salt', function (): void {
        $model = ModelA::factory()->create();
        $hash  = $model->hashId;

        Config::set(ConfigParameters::SALT, Str::random());

        $newHash = ModelA::findOrFail($model->getKey())->hashId;

        expect($newHash)->not->toEqual($hash);
    });

    it('can define model hash id length', function (): void {
        $randomLength = fake()->numberBetween(5, 20);
        Config::set(ConfigParameters::LENGTH, $randomLength);

        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;

        $expectedLength = mb_strlen(Config::get(ConfigParameters::SEPARATOR)) +
            Config::get(ConfigParameters::PREFIX_LENGTH) +
            $randomLength;

        expect(mb_strlen($hashId))->toEqual($expectedLength);
    });

    it('can define model hash id alphabet', function (): void {
        $customAlphabet = 'abcdef1234567890';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model   = ModelA::factory()->create();
        $hashId  = $model->hashId;
        $parsed  = Generator::parseHashIdForModel($hashId);
        $allowed = mb_str_split($customAlphabet);

        foreach (mb_str_split($parsed->hashIdForKey) as $char) {
            expect($allowed)->toContain($char);
        }
    });

    it('can define model hash id alphabet from emojis', function (): void {
        $customAlphabet = '😀😃😄😁😆😅😂🤣🥲☺️😊😇🙂🙃😉😌';
        Config::set(ConfigParameters::ALPHABET, $customAlphabet);

        $model   = ModelA::factory()->create();
        $hashId  = $model->hashId;
        $parsed  = Generator::parseHashIDForModel($hashId);
        $allowed = mb_str_split($customAlphabet);

        foreach (mb_str_split($parsed->hashIdForKey) as $char) {
            expect($allowed)->toContain($char);
        }
    });
});

describe('static functions', function (): void {
    it('can get a model key from hash id', function (): void {
        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;

        expect(ModelA::keyFromHashId($hashId))->toEqual($model->getKey());
    });

    it('returns null if hash id can not be parsed', function (): void {
        expect(ModelA::keyFromHashId('non-existing-hash-id'))->toBeNull();
    });

    it('returns null if hash id is valid format but decodes to empty', function (): void {
        $model     = ModelA::factory()->create();
        $hashId    = $model->hashId;
        $invalidId = mb_substr($hashId, 0, -1).'0';

        expect(ModelA::keyFromHashId($invalidId))->toBeNull();
    });

    it('returns null if hash id prefix does not match model prefix', function (): void {
        Config::set(ConfigParameters::PREFIX, 'a_custom_prefix', ModelA::class);
        Config::set(ConfigParameters::PREFIX, 'b_custom_prefix', ModelB::class);

        ModelA::factory()->create();
        $modelB = ModelB::factory()->create();

        expect(ModelA::keyFromHashId($modelB->hashId))->toBeNull();
    });
});

describe('accessors', function (): void {
    it('has a hash id attribute', function (): void {
        $model  = ModelA::factory()->create();
        $hashId = $model->hashId;

        expect(ModelA::keyFromHashId($hashId))->toEqual($model->getKey());
    });

    it('has a hash id raw attribute', function (): void {
        $model = ModelA::factory()->create();

        $hashIdRaw = Generator::parseHashIDForModel($model->hashId)->hashIdForKey;

        expect($model->hashIdRaw)->toEqual($hashIdRaw);
    });

    it('returns null if model does not have a key for hash id raw', function (): void {
        $model = ModelA::factory()->make();

        expect($model->hashIdRaw)->toBeNull();
    });
});
