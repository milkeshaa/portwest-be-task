<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameColumnUppercase;
use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use HasFactory;
    use HasStringIdColumn;
    use HasNameColumnUppercase;
    
    protected $keyType = 'string';
    public $incrementing = false;
}
