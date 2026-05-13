<?php

namespace Database\Factories;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tkbm>
 */
class TkbmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'karyawan_id' => $this->faker->numberBetween(3, 8),
            // 'penjualan_id' => Penjualan::factory(),
        ];
    }
}
