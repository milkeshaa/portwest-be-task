<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasNameColumnUppercase;
use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

/**
 * @param string $id
 * @param string $name
 * 
 * @param Collection<ProductVariant> $variants
 * @param Product $product
 */
class Sku extends Model
{
    use HasFactory;
    use HasStringIdColumn;
    use HasNameColumnUppercase;
    
    protected $keyType = 'string';
    public $incrementing = false;

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(
            Product::class,
            ProductVariant::class,
            'sku_id',
            'id',
            'id',
            'product_id',
        );
    }
}
