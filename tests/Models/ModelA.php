<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelA extends Model
{
    use HasFactory;
    use HasHashIDs;

    protected $fillable = ['name'];
}
