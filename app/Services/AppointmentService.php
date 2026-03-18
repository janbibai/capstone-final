<?php 
namespace App\Services;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function schedule(array $data)
{
    return DB::transaction(function () use ($data) {

        // check if exist na ang booking for the SAME patient
        $existsForPatient = Appointment::where('patient_id', '=', $data['patient_id'])
            ->where('schedule', '=', $data['schedule'])
            ->where('schedule_time', '=', $data['schedule_time'])
            ->where('status', '=', 'not started')
            ->exists();

        if ($existsForPatient) {
            throw new \Exception('You have already booked this exact slot.');
        }

        // check if slot is taken by ANY patient
        $slotTaken = Appointment::where('schedule', '=', $data['schedule'])
            ->where('schedule_time', '=', $data['schedule_time'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($slotTaken) {
            throw new \Exception('This schedule slot is already taken by another patient.');
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


    public function getByDate($date){
        return Appointment::where('schedule', $date)
            ->orderBy('schedule_time')
            ->get();
    }

}
?>