<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameColumnLowercase;
use App\Models\Traits\HasNameColumnUcfirst;
use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @param string $id
 * @param string $name
 * 
 * @param Collection<Product> $products
 */
class Category extends Model
{
    use HasFactory;
    use HasStringIdColumn;
    use HasNameColumnLowercase;
    use HasNameColumnUcfirst;

    protected $keyType = 'string';
    public $incrementing = false;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
