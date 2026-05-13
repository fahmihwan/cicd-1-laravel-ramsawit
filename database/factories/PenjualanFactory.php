<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penjualan>
 */

use Illuminate\Support\Str;

class PenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'id' => (string) Str::uuid(),
            'pabrik_id'      =>  $this->faker->numberBetween(1, 2),
            'sopir_id'        =>  $this->faker->numberBetween(1, 2),
            // 'do_type_id'      =>  $this->faker->numberBetween(1, 2),
            'model_kerja_id' => 1,
            'do_type_id' => 1,
            'tarif_sopir_id' => 1,
            'tarif_tkbm_id'  => 2,
            'timbangan_first'  => $this->faker->numberBetween(4000, 5000),
            'timbangan_second' => $this->faker->numberBetween(2000, 3000),
            'bruto'            => $this->faker->numberBetween(1000, 5000),
            'sortasi'          => $this->faker->randomFloat(2, 1, 10),
            'netto'            => $this->faker->numberBetween(1000, 5000),
            'harga'            => $this->faker->numberBetween(5000, 10000),
            'uang'             => $this->faker->numberBetween(2000, 3000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    public function createdWithinLastWeek(int $index, int $total)
    {
        return $this->state(function (array $attributes) use ($index, $total) {
            // Hitung timestamp awal (1 minggu lalu)
            $startDate = Carbon::now()->subWeek();
            // Hitung jarak dalam detik antara start sampai now
            $diffInSeconds = Carbon::now()->diffInSeconds($startDate);
            // Hitung posisi timestamp berdasarkan index
            $secondsToAdd = intval(($diffInSeconds / max($total - 1, 1)) * $index);
            $createdAt = $startDate->copy()->addSeconds($secondsToAdd);
            return [
                'tanggal_penjualan' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        });
    }
}
