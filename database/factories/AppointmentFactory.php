<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => null, // we will attach later
            'service_id' => 1, // change if needed
            'schedule' => Carbon::today(),
            'schedule_time' => fake()->time(),
            'status' => 'not started',
        ];
    }
}
