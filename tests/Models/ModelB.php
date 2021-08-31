<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Deligoez\LaravelModelHashIDs\Traits\HasHashIDRouting;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelB extends Model
{
    use HasFactory;
    use HasHashIDs;
    use HasHashIDRouting;

    protected $fillable = ['title'];
}
