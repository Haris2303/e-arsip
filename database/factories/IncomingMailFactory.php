<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncomingMail>
 */
class IncomingMailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'mail_number' => 'SM-' . $this->faker->unique()->numerify('#######'),
            'mail_date' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'received_date' => now(),
            'sender' => $this->faker->company,
            'agenda_number' => $this->faker->unique()->numberBetween(1, 999),
            'subject' => $this->faker->sentence(6),
            'priority' => $this->faker->randomElement(['very urgent', 'urgent', 'confidential']),
            'notes' => $this->faker->optional()->paragraph,
            'expected_actions' => $this->faker->randomElements([
                'Proses Lebih Lanjut',
                'Koordinasi/Konfirmasi',
                'Monitor Perkembangan',
                'Untuk Menjadi Perhatian',
                'Tanggapan dan Saran',
                'Laporkan',
                'Bicarakan Bersama'
            ], rand(1, 7)),
            'file_path' => null, // atau pakai $this->faker->filePath() jika pakai dummy
            'status' => 'active',
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
            'updated_at' => now(),
        ];
    }
}
