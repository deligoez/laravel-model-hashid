<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Deligoez\LaravelModelHashIDs\Models\Concerns\HasHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelA extends Model
{
    use HasFactory;
    use HasHashIDs;

    protected $fillable = ['name'];
}