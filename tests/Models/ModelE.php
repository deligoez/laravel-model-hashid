<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Deligoez\LaravelModelHashId\Traits\SerializesHashId;

/**
 * Class ModelE.
 *
 * @property string $name
 */
class ModelE extends Model
{
    use HasFactory;
    use HasHashId;
    use SerializesHashId;

    protected $fillable = ['name'];
}
