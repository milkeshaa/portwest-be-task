<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @param string $id
 * @param string $name
 * @param string $category_id
 * @param ?string $description
 * 
 * @param Category $category
 * @param Collection<ProductVariant> $variants
 */
class Product extends Model
{
    use HasFactory;
    use HasStringIdColumn;

    protected $keyType = 'string';
    public $incrementing = false;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
}
