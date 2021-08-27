<?php

namespace Deligoez\LaravelModelHashids\Commands;

use Illuminate\Console\Command;

class LaravelModelHashidsCommand extends Command
{
    public $signature = 'laravel-model-hashids';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
