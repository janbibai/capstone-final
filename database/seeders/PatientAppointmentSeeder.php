<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PatientAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Poblacion patients (service_id 1)
        $queue = 1;

        Patient::factory(50)->create()->each(function ($patient) use (&$queue) {

            Appointment::create([
                'patient_id' => $patient->id,
                'service_id' => 1,
                'schedule' => Carbon::today(),
                'schedule_time' => now()->addMinutes($queue * 5)->format('H:i:s'),
                'queue_number' => str_pad($queue, 3, '0', STR_PAD_LEFT),
                'status' => 'not started',
            ]);

            $queue++;
        });

        // Purok 9 patients
        $purok9Service = Service::where('department_id', 2)->first();

        if ($purok9Service) {
            $queue = 1;

            Patient::factory(50)->create()->each(function ($patient) use (&$queue, $purok9Service) {

                Appointment::create([
                    'patient_id' => $patient->id,
                    'service_id' => $purok9Service->id,
                    'schedule' => Carbon::today(),
                    'schedule_time' => now()->addMinutes($queue * 5)->format('H:i:s'),
                    'queue_number' => str_pad($queue, 3, '0', STR_PAD_LEFT),
                    'status' => 'not started',
                ]);

                $queue++;
            });
        }
    }
}
