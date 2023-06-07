<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Traits\SavesHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelC extends Model
{
    use HasFactory;
    use HasHashId;
    use SavesHashId;

    protected $fillable = [
        'slug',
        'hash_id',
    ];
}
