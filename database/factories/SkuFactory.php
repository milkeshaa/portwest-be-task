<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sku>
 */
class SkuFactory extends Factory
{
    protected $model = Sku::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->toUpper(Str::random(5)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
