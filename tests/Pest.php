<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\WithFaker;
use Deligoez\LaravelModelHashId\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class)->in(__DIR__);
