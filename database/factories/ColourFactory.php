<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Colour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Colour>
 */
class ColourFactory extends Factory
{
    protected $model = Colour::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => strtolower($this->faker->colorName()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
