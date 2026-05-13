<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian_tbs>
 */
class Pembelian_tbsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_customer' => $this->faker->name(),
            'tbs_type_id'       => $this->faker->numberBetween(1, 3),
            'netto'             => $this->faker->numberBetween(500, 2000),
            'harga'             => $this->faker->numberBetween(1000, 5000),
            'uang'              => $this->faker->numberBetween(500000, 1000000),
            'timbangan_first'   => $this->faker->numberBetween(1000, 3000),
            'timbangan_second'  => $this->faker->numberBetween(500, 1000),
            'bruto'             => $this->faker->numberBetween(1200, 3200),
            'sortasi'           => $this->faker->numberBetween(0, 100),
        ];
    }
}
