<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Commands;

use Illuminate\Console\Command;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Support\Generator;

class DecodeHashIdCommand extends Command
{
    protected $signature   = 'hashid:decode {hashid : The hash ID to decode} {model? : The fully qualified model class name (optional)}';
    protected $description = 'Decode a hash ID to its integer key';

    public function handle(): int
    {
        /** @var string $hashId */
        $hashId     = $this->argument('hashid');
        $modelClass = $this->argument('model');

        if ($modelClass !== null) {
            return $this->decodeWithModel($hashId, $modelClass);
        }

        return $this->decodeWithoutModel($hashId);
    }

    private function decodeWithModel(string $hashId, string $modelClass): int
    {
        if (!class_exists($modelClass)) {
            $this->error("Class [{$modelClass}] does not exist.");

            return self::FAILURE;
        }

        if (!in_array(HasHashId::class, class_uses_recursive($modelClass), true)) {
            $this->error("Class [{$modelClass}] does not use the HasHashId trait.");

            return self::FAILURE;
        }

        /** @var int|null $key */
        $key = $modelClass::keyFromHashId($hashId);

        if ($key === null) {
            $this->error('Could not decode the given hash ID.');

            return self::FAILURE;
        }

        $prefix    = Generator::buildPrefixForModel($modelClass);
        $parsed    = Generator::parseHashIDForModel($hashId, $modelClass);
        $separator = $parsed !== null ? $parsed->separator : '_';

        $this->table(
            ['Field', 'Value'],
            [
                ['Key', (string) $key],
                ['Model', $modelClass],
                ['Prefix', $prefix],
                ['Separator', $separator],
            ],
        );

        return self::SUCCESS;
    }

    private function decodeWithoutModel(string $hashId): int
    {
        $parsed = Generator::parseHashIDForModel($hashId);

        if ($parsed === null) {
            $this->error('Could not decode the given hash ID.');

            return self::FAILURE;
        }

        $resolvedClass = $parsed->modelClassName;

        if ($resolvedClass !== null) {
            $generator = Generator::build($resolvedClass);
        } else {
            $this->error('Could not decode the given hash ID.');

            return self::FAILURE;
        }

        $decoded = $generator->decode($parsed->hashIdForKey);
        $key     = $decoded[0] ?? null;

        if ($key === null) {
            $this->error('Could not decode the given hash ID.');

            return self::FAILURE;
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['Key', (string) $key],
                ['Model', $resolvedClass],
                ['Prefix', $parsed->prefix],
                ['Separator', $parsed->separator],
            ],
        );

        return self::SUCCESS;
    }
}
