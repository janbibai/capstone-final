<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Http\Requests\StorePatientRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use App\Services\AppointmentService;
use App\Services\PatientService;
use Illuminate\Http\Request;


class PatientController extends Controller
{   
    protected AppointmentService $appointmentService;
    protected PatientService $patientService;

    public function __construct(
        PatientService $patientService,
        AppointmentService $appointmentService
    ) 
    {
        $this->patientService = $patientService;
        $this->appointmentService = $appointmentService;
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();

        return view('appointment.create', compact('services'));
    }
    
    public function start($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'started'
        ]);

        broadcast(new QueueUpdated($appointment->queue_number))->toOthers();

        return back();
    }


    
    public function storePatient(StorePatientRequest $request)
    {
        $data = $request->validated();

        // register patient (pass the uploaded ID file)
        $patient = $this->patientService->register($data, $request->file('valid_id'));

        try {
            // mao ni mo create og appointment
            $appointment = $this->appointmentService->schedule([
                'patient_id' => $patient->id,
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],
                'schedule_time' => $data['schedule_time'],
            ]);

            return redirect()
                ->route('appointment.create')
                ->with('success', 'Appointment booked successfully!Your queue number is Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT));
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['schedule_time' => $e->getMessage()]);
        }
    }

    public function getBookedTimes(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json([]);
        }

        $bookedTimes = Appointment::where('schedule', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('schedule_time')
            ->toArray();

        $formattedTimes = array_map(function($time) {
            return date('H:i', strtotime($time));
        }, $bookedTimes);

        return response()->json($formattedTimes);
    }

    public function queueStatus()
    {
        return view('appointment.queue-status');
    }

    public function getQueueStatusData(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['current_serving' => null, 'appointments' => []]);
        }

        $appointments = Appointment::with('patient')
            ->where('schedule', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('queue_number', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'queue_number' => 'Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT),
                    'status' => $appointment->status,
                    'schedule_time' => date('h:i A', strtotime($appointment->schedule_time)),
                    // Optional: masking last name for some privacy
                    'patient_name' => $appointment->patient->first_name . ' ' . mb_substr($appointment->patient->last_name, 0, 1) . '.',
                ];
            });

        $currentServing = $appointments->where('status', 'started')->first();

        return response()->json([
            'current_serving' => $currentServing,
            'appointments' => $appointments->values()
        ]);
    }
}
