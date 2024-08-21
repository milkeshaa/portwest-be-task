<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameColumnLowercase;
use App\Models\Traits\HasNameColumnUcfirst;
use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @param string $id
 * @param string $name
 */
class Colour extends Model
{
    use HasFactory;
    use HasStringIdColumn;
    use HasNameColumnLowercase;
    use HasNameColumnUcfirst;

    protected $keyType = 'string';
    public $incrementing = false;
}
