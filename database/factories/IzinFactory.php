<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Izin>
 */
class IzinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $all_id_users = \App\Models\User::all()->pluck('id')->toArray();

        $tgl_mulai = $this->faker->dateTimeBetween('-1 month', 'now');
        $tgl_selesai = $this->faker->dateTimeBetween($tgl_mulai, '+1 month');
        return [
            'id_user' => $this->faker->randomElement($all_id_users),
            'jenis' => $this->faker->randomElement(['cuti', 'sakit', 'izin']),
            // tgl_mulai can be current date or future nearer date
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'bukti' => $this->faker->imageUrl(),
            'keterangan' => $this->faker->text(),
            // tracking, json format
            'tracking' => json_encode([
                'created_at' => $this->faker->dateTime(),
                'created_by' => $this->faker->randomElement($all_id_users),
                'updated_at' => $this->faker->dateTime(),
                'updated_by' => $this->faker->randomElement($all_id_users),
                'deleted_at' => $this->faker->dateTime(),
                'deleted_by' => $this->faker->randomElement($all_id_users),
            ]),
            'reason' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'accepted_pimpinan', 'accepted_admin', 'accepted_kabid', 'rejected']),
        ];
    }
}
