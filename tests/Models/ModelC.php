<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashIDs\Tests\Models;

use Deligoez\LaravelModelHashIDs\Traits\HasHashIDs;
use Deligoez\LaravelModelHashIDs\Traits\SavesHashIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelC extends Model
{
    use HasFactory;
    use HasHashIDs;
    use SavesHashIDs;

    protected $fillable = [
        'slug',
        'hash_id'
    ];
}
