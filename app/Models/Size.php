<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameColumnUppercase;
use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @param string $id
 * @param string $name
 */
class Size extends Model
{
    use HasFactory;
    use HasStringIdColumn;
    use HasNameColumnUppercase;
    
    protected $keyType = 'string';
    public $incrementing = false;
}
