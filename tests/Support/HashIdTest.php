<?php

declare(strict_types=1);

use Hashids\HashidsInterface;
use Deligoez\LaravelModelHashId\Support\HashId;
use Deligoez\LaravelModelHashId\Support\Config;
use Deligoez\LaravelModelHashId\Support\ConfigParameters;

describe('encode', function (): void {
    it('encodes with default config', function (): void {
        $hashId = HashId::encode(1234);

        expect($hashId)->toBeString()->not->toBeEmpty();
    });

    it('encodes with custom prefix and separator', function (): void {
        $hashId = HashId::encode(1234, prefix: 'tok', separator: '_');

        expect($hashId)->toStartWith('tok_');
    });

    it('encodes with custom salt, length, and alphabet', function (): void {
        $hashId = HashId::encode(1234, salt: 'custom-salt', length: 8, alphabet: 'abcdefghjklmnopqrstuvwxyz');

        // prefix-less, so raw hash only
        expect(mb_strlen($hashId))->toBe(8);
    });

    it('produces no separator when prefix is null', function (): void {
        $hashId = HashId::encode(1234);

        // No prefix → no separator in output
        expect($hashId)->not->toContain('_');
    });
});

describe('decode', function (): void {
    it('decodes with default config', function (): void {
        $hashId = HashId::encode(1234);

        expect(HashId::decode($hashId))->toBe(1234);
    });

    it('roundtrips encode and decode', function (): void {
        $id     = fake()->numberBetween(1, 100000);
        $hashId = HashId::encode($id);

        expect(HashId::decode($hashId))->toBe($id);
    });

    it('decodes with custom prefix and separator', function (): void {
        $hashId = HashId::encode(5678, prefix: 'tok', separator: '_');

        expect(HashId::decode($hashId, prefix: 'tok', separator: '_'))->toBe(5678);
    });

    it('returns null when decoding with wrong prefix', function (): void {
        $hashId = HashId::encode(1234, prefix: 'tok', separator: '_');

        expect(HashId::decode($hashId, prefix: 'wrong', separator: '_'))->toBeNull();
    });

    it('decodes with custom salt, length, and alphabet', function (): void {
        $salt     = 'custom-salt';
        $length   = 8;
        $alphabet = 'abcdefghjklmnopqrstuvwxyz';

        $hashId = HashId::encode(42, salt: $salt, length: $length, alphabet: $alphabet);

        expect(HashId::decode($hashId, salt: $salt, length: $length, alphabet: $alphabet))->toBe(42);
    });

    it('returns null for invalid hash', function (): void {
        expect(HashId::decode('totally-invalid-hash'))->toBeNull();
    });
});

describe('buildGenerator', function (): void {
    it('builds a standalone generator instance', function (): void {
        $generator = HashId::buildGenerator();

        expect($generator)->toBeInstanceOf(HashidsInterface::class);
    });

    it('builds a generator with custom parameters', function (): void {
        $generator = HashId::buildGenerator(salt: 'my-salt', length: 10, alphabet: 'abcdefghjklmnopqrstuvwxyz');
        $encoded   = $generator->encode(99);

        expect(mb_strlen($encoded))->toBe(10);
    });
});
