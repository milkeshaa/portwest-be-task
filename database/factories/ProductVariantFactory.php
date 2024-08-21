<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'product_id' => ProductFactory::new(),
            'colour_id' => ColourFactory::new(),
            'size_id' => SizeFactory::new(),
            'sku_id' => SkuFactory::new(),
            'on_sale' => false,
            'box_qty' => 0,
            'width' => 0,
            'height' => 0,
            'length' => 0,
            'upcoming_update_date' => now()->format('m/d/Y'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
