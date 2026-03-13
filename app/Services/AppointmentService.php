<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function schedule(array $data)
    {
        return DB::transaction(function () use ($data) {

            // check if booking already exists
            $exists = Appointment::where('patient_id', $data['patient_id'])
                ->where('schedule', $data['schedule'])
                ->where('schedule_time', $data['schedule_time'])
                ->where('status', 'not started')
                ->exists();

            if ($exists) {
                throw new \Exception('You already have an appointment booked for this date and time.');
            }

            // Get last queue number for that date
            $lastQueue = Appointment::where('schedule', $data['schedule'])
                ->max('queue_number');

            $nextQueue = $lastQueue ? $lastQueue + 1 : 1;

            // Create appointment with queue number
            return Appointment::create([
                'patient_id' => $data['patient_id'],
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],
                'schedule_time' => $data['schedule_time'],
                'queue_number' => $nextQueue,
                'status' => 'not started',
            ]);
        });
    }


    public function getByDate($date)
    {
        return Appointment::where('schedule', $date)
            ->orderBy('schedule_time')
            ->get();
    }
}