<?php

declare(strict_types=1);

namespace App\Models;

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

    protected $fillable = [
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
