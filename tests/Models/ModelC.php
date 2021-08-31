<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;
use Deligoez\LaravelModelHashIDs\Traits\SavesHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelC extends Model
{
    use HasFactory;
    use HasHashIDs;
    use SavesHashIDs;

    protected $fillable = [
        'slug',
        'hash_id',
    ];
}
