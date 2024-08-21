<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @param string $id
 * @param bool $on_sale
 * @param int $box_qty
 * @param float $width
 * @param float $height
 * @param float $length
 * @param string $name
 * 
 * @param Product $product
 * @param Sku $sku
 * @param Size $size
 * @param Colour $colour
 */
class ProductVariant extends Model
{
    use HasFactory;
    use HasStringIdColumn;

    protected $fillable = [
        'on_sale',
        'box_qty',
        'width',
        'height',
        'length',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class);
    }

    public function size(): HasOne
    {
        return $this->hasOne(Size::class);
    }

    public function colour(): HasOne
    {
        return $this->hasOne(Colour::class);
    }

    public function getNameAttribute(): string
    {
        return sprintf('%s%s%s', $this->sku->name, $this->size->name, $this->colour->name);
    }
}
