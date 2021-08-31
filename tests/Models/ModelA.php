<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDRouting;

class ModelA extends Model
{
    use HasFactory;
    use HasHashIDs;
    use HasHashIDRouting;

    protected $fillable = ['name'];
}
