<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Deligoez\LaravelModelHashId\Tests\Models\ModelA;

it('compiles to correct PHP', function (): void {
    $compiled = Blade::compileString('@hashid($model)');

    expect($compiled)->toBe('<?php echo e($model->hashId); ?>');
});

it('renders hash id for a model', function (): void {
    $model = ModelA::factory()->create();

    $rendered = Blade::render('@hashid($model)', ['model' => $model]);

    expect($rendered)->toBe($model->hashId);
});
