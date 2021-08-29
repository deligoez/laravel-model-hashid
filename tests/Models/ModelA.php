<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;

class ModelA extends Model
{
    use HasFactory;
    use HasHashIDs;

    protected $fillable = ['name'];
}
