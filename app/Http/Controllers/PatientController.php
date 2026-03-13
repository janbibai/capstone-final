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

        try {
            // register patient
            $patient = $this->patientService->register($data);
        } catch (\Exception $e) {
            // If patient already exists, find them instead of crashing
            $patient = Patient::where('first_name', $data['first_name'])
                ->where('last_name', $data['last_name'])
                ->where('date_of_birth', $data['date_of_birth'])
                ->where('gender', $data['gender'])
                ->first();

            if (!$patient) {
                return redirect()
                    ->route('appointment.create')
                    ->withErrors(['general' => $e->getMessage()])
                    ->withInput();
            }
        }

        try {
            // create appointment
            $appointment = $this->appointmentService->schedule([
                'patient_id' => $patient->id,
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],
                'schedule_time' => $data['schedule_time'],
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('appointment.create')
                ->withErrors(['general' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('appointment.create')
            ->with('success', 'Appointment booked successfully! Your queue number is Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT));
    }


}
