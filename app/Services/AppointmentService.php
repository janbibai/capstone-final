<?php 
namespace App\Services;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    /**
     * @param array $data
     * @return Appointment
     * @throws \Exception
     */
    public function schedule(array $data)
{
    return DB::transaction(function () use ($data) {

        // check if exist na ang booking for the SAME patient today
        $existsForPatient = Appointment::where('patient_id', '=', $data['patient_id'])
            ->where('schedule', '=', $data['schedule'])
            ->where('status', '=', 'not started')
            ->exists();

        if ($existsForPatient) {
            throw new \Exception('You already have an active queue today.');
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


    /**
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByDate($date){
        return Appointment::where('schedule', $date)
            ->orderBy('schedule_time')
            ->get();
    }

}
?>