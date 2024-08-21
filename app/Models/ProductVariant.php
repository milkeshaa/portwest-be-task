<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasStringIdColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @param string $id
 * @param bool $on_sale
 * @param int $box_qty
 * @param float $width
 * @param float $height
 * @param float $length
 * @param string $name
 * @param ?string $upcoming_update_date
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
    
    protected $keyType = 'string';
    public $incrementing = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function colour(): BelongsTo
    {
        return $this->belongsTo(Colour::class);
    }

    public function getNameAttribute(): string
    {
        return sprintf('%s%s%s', $this->sku->name, $this->size->name, $this->colour->name);
    }
}
