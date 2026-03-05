<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Appointment;
use Carbon\Carbon;

class PatientAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $queue = 1;

        Patient::factory(50)->create()->each(function ($patient) use (&$queue) {

            Appointment::create([
                'patient_id' => $patient->id,
                'service_id' => 1,
                'schedule' => Carbon::today(),
                'schedule_time' => now()->addMinutes($queue * 5)->format('H:i:s'),
                'queue_number' => str_pad($queue, 3, '0', STR_PAD_LEFT),
                'status' => 'not started'
            ]);

            $queue++;
        });
    }
}
