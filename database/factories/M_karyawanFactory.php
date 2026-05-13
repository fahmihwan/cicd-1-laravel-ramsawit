<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\M_karyawan>
 */
class M_karyawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'main_type_karyawan_id' => 2
            // 'type_karyawan_id' => 2
            // $this->faker->randomElement(['TKBM', 'SOPIR']),
        ];
    }
}
