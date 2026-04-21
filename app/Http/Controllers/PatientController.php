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
    ) {
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
        /** @var \App\Models\Patient $patient */
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

        $formattedTimes = array_map(function ($time) {
            return date('H:i', strtotime($time));
        }, $bookedTimes);

        return response()->json($formattedTimes);
    }

    public function lookupPatient(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'date_of_birth' => 'required|date',
        ]);

        $patient = Patient::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->whereDate('date_of_birth', $request->date_of_birth)
            ->first();

        if (!$patient) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'id' => $patient->id,
            'name' => $patient->full_name,
        ]);
    }

    public function storeExistingPatient(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_id' => 'required|exists:services,id',
            'schedule' => 'required|date|after_or_equal:today',
            'schedule_time' => 'required|date_format:H:i',
        ]);

        // Block if patient already has an active appointment on the chosen date
        $alreadyBooked = Appointment::where('patient_id', $data['patient_id'])
            ->where('schedule', $data['schedule'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($alreadyBooked) {
            return back()->withInput()->withErrors([
                'schedule' => 'You already have an active appointment on this date. Please choose a different date.',
            ]);
        }

        try {
            /** @var \App\Models\Appointment $appointment */
            $appointment = $this->appointmentService->schedule([
                'patient_id' => $data['patient_id'],
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],
                'schedule_time' => $data['schedule_time'],
            ]);

            return redirect()
                ->route('appointment.create')
                ->with('success', 'Appointment booked successfully! Your queue number is Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT));
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['schedule_time' => $e->getMessage()]);
        }
    }

    public function queueStatus()
    {
        return view('appointment.queue-status');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQueueStatusData(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['current_serving' => null, 'appointments' => []]);
        }

        // Fetch current serving independently of pagination
        $currentServingRaw = Appointment::with('patient')
            ->where('schedule', $date)
            ->where('status', 'started')
            ->first();

        $currentServing = null;
        if ($currentServingRaw) {
            $currentServing = [
                'queue_number' => 'Q-' . str_pad($currentServingRaw->queue_number, 3, '0', STR_PAD_LEFT),
                'status' => $currentServingRaw->status,
                'schedule_time' => date('h:i A', strtotime($currentServingRaw->schedule_time)),
            ];
        }

        // Paginate queue list
        $appointments = Appointment::with('patient')
            ->where('schedule', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('queue_number', 'asc')
            ->paginate(15);

        $appointments->through(function (Appointment $appointment) {
            return [
                'queue_number' => 'Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT),
                'status' => $appointment->status,
                'schedule_time' => date('h:i A', strtotime($appointment->schedule_time)),
            ];
        });

        return response()->json([
            'current_serving' => $currentServing,
            'appointments' => $appointments
        ]);
    }
}
