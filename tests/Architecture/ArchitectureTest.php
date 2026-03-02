<?php

declare(strict_types=1);

arch('no debugging functions are used')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('all source files use strict types')
    ->expect('Deligoez\LaravelModelHashId')
    ->toUseStrictTypes();

arch('traits are traits')
    ->expect('Deligoez\LaravelModelHashId\Traits')
    ->toBeTraits();

arch('mixins are classes')
    ->expect('Deligoez\LaravelModelHashId\Mixins')
    ->toBeClasses();

arch('casts are classes')
    ->expect('Deligoez\LaravelModelHashId\Casts')
    ->toBeClasses();

arch('commands are classes')
    ->expect('Deligoez\LaravelModelHashId\Commands')
    ->toBeClasses();
