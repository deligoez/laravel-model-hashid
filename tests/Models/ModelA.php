<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;

/**
 * Class ModelA.
 *
 * @property string $name
 */
class ModelA extends Model
{
    use HasFactory;
    use HasHashId;
    use HasHashIdRouting;

    protected $fillable = ['name'];
}
