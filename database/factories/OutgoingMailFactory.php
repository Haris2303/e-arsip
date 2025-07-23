<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OutgoingMail>
 */
class OutgoingMailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-6 month', 'now');

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'subject' => $this->faker->sentence(),
            'mail_number' => $this->faker->unique()->numerify('SK-###/EXT'),
            'recipient' => $this->faker->company(),
            'agenda_number' => $this->faker->numerify('AGD-###'),
            'mail_date' => $this->faker->date(),
            'attachment' => $this->faker->word(),
            'notes' => $this->faker->text(100),
            'file_path' => null, // File bisa kamu isi setelah upload manual jika perlu
            'status' => $this->faker->randomElement(['active', 'archived']),
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now'),
        ];
    }
}
