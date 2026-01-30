<?php 
namespace App\Services;
use App\Models\Appointment;

class AppointmentService
{
    public function schedule(array $data){
        $exists = Appointment::where('patient_id', $data ['patient_id'])
            ->where('schedule', $data ['schedule'])
            ->where('schedule_time', $data ['schedule_time'])
            ->where('status', 'not started')
            ->exists();

        if ($exists){
            throw new \Exception('you have already booked');
        }

        return Appointment::create([
                'patient_id' => $data['patient_id'],
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],       
                'schedule_time' => $data['schedule_time'],       
                'status' => 'not started',

        ]);
    }

    public function getByDate($date){
        return Appointment::where('schedule', $date)
            ->orderBy('schedule_time')
            ->get();
    }

}


?>