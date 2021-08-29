<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Support;

use Deligoez\LaravelModelHashIDs\Support\ModelHashIDGenerator;
use Deligoez\LaravelModelHashIDs\Tests\Models\ModelA;
use Deligoez\LaravelModelHashIDs\Tests\TestCase;
use Config;

class ModelHashIDGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_build_a_lowercase_prefix_from_a_model(): void
    {
        // 1️⃣ Arrange 🏗
        Config::set('hashids.prefix_lenght', 3);
        Config::set('hashids.prefix_case', 'lower');

        $model = new ModelA();

        // 2️⃣ Act 🏋🏻‍
        $prefix = ModelHashIDGenerator::buildPrefixForModel($model);

        // 3️⃣ Assert ✅
        $this->assertEquals('mod', $prefix);
    }
}