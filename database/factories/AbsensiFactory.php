<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\JamKerja;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $all_id_users = User::all()->pluck('id')->toArray();
        $all_id_jam_kerja = JamKerja::all()->pluck('id')->toArray();

        $tanggal = $this->faker->dateTimeBetween('-1 month', 'now');
        return [
            'id_user' => $this->faker->randomElement($all_id_users),
            'id_jam' => $this->faker->randomElement($all_id_jam_kerja),
            'tanggal' => $tanggal,
            'waktu_masuk' => $this->faker->time(),
            'waktu_keluar' => $this->faker->time(),
            // latitude, longitude, double
            'lat_masuk' => $this->faker->latitude(),
            'long_masuk' => $this->faker->longitude(),
            'lat_keluar' => $this->faker->latitude(),
            'long_keluar' => $this->faker->longitude(),
            'total_jam' => $this->faker->randomFloat(2, 0, 24),
            'dok_masuk' => $this->faker->imageUrl(),
            'dok_keluar' => $this->faker->imageUrl(),
            'jarak_masuk' => $this->faker->randomFloat(2, 0, 100),
            'jarak_keluar' => $this->faker->randomFloat(2, 0, 100),
            'lokasi_masuk' => $this->faker->address(),
            'lokasi_keluar' => $this->faker->address(),
            'status' => $this->faker->randomElement(['hadir', 'izin', 'cuti', 'dinas', 'off']),
        ];
    }
}
