<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    protected $model = Size::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => strtoupper($this->faker->randomElement(['xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl', '5xl'])),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
