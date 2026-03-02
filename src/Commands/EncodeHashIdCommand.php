<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Commands;

use Illuminate\Console\Command;
use Deligoez\LaravelModelHashId\Traits\HasHashId;

class EncodeHashIdCommand extends Command
{
    protected $signature   = 'hashid:encode {model : The fully qualified model class name} {id : The integer key to encode}';
    protected $description = 'Encode an integer key to a hash ID for a given model';

    public function handle(): int
    {
        /** @var string $modelClass */
        $modelClass = $this->argument('model');
        $id         = (int) $this->argument('id');

        if (!class_exists($modelClass)) {
            $this->error("Class [{$modelClass}] does not exist.");

            return self::FAILURE;
        }

        if (!in_array(HasHashId::class, class_uses_recursive($modelClass), true)) {
            $this->error("Class [{$modelClass}] does not use the HasHashId trait.");

            return self::FAILURE;
        }

        $model                         = new $modelClass();
        $model->{$model->getKeyName()} = $id;

        $this->info($model->hashId);

        return self::SUCCESS;
    }
}
