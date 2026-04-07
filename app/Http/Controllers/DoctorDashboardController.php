<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $departmentId = auth()->user()->staff->department_id;

        $appointments = Appointment::with(['patient', 'service'])
            ->where('schedule', $date)
            ->whereHas('service', fn ($q) => $q->where('department_id', $departmentId))
            ->orderBy('queue_number')
            ->orderBy('schedule_time')
            ->paginate(15);

        return view('doctor.dashboard', [
            'date' => $date,
            'appointments' => $appointments,
        ]);
    }

    public function addRecord(Request $request, Patient $patient)
    {
        $appointmentId = $request->query('appointment_id');

        if ($appointmentId) {
            $appointment = Appointment::where('id', $appointmentId)
                ->where('patient_id', $patient->id)
                ->first();
        } else {
            $appointment = Appointment::where('patient_id', $patient->id)
                ->where('schedule', now()->toDateString())
                ->orderByDesc('schedule_time')
                ->first();
        }

        if (! $appointment || $appointment->status !== 'started') {
            return redirect()
                ->route('doctor.dashboard', ['date' => now()->toDateString()])
                ->withErrors(['appointment' => 'You can only add a diagnosis for a patient whose appointment has been started by staff.']);
        }

        $diagnoses = Diagnosis::orderBy('name')->get();
        $currentRecord = MedicalRecord::with('diagnosis')
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_on')
            ->first();

        return view('doctor.add-record', [
            'patient' => $patient,
            'diagnoses' => $diagnoses,
            'currentRecord' => $currentRecord,
            'appointment' => $appointment,
        ]);
    }

    public function storeRecord(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis_id' => 'nullable|exists:diagnoses,id',
            'diagnosis_name' => 'nullable|string|max:255',
            'details' => 'required|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        if ((int) $appointment->patient_id !== (int) $validated['patient_id']) {
            return back()
                ->withErrors(['appointment_id' => 'The selected appointment does not belong to this patient.'])
                ->withInput();
        }

        if ($appointment->status !== 'started') {
            return back()
                ->withErrors(['appointment_id' => 'You can only add or update a diagnosis when the appointment status is "started".'])
                ->withInput();
        }

        $diagnosisId = $validated['diagnosis_id'] ?? null;
        $diagnosisName = trim($validated['diagnosis_name'] ?? '');

        if (! $diagnosisId && $diagnosisName === '') {
            return back()->withErrors(['diagnosis_id' => 'Either select an existing diagnosis or enter a new diagnosis name.'])->withInput();
        }

        if (! $diagnosisId) {
            $diagnosis = Diagnosis::firstOrCreate(
                ['name' => $diagnosisName],
                [
                    'created_by' => auth()->id(),
                    'created_on' => now(),
                ]
            );
            $diagnosisId = $diagnosis->id;
        }

        $currentRecord = MedicalRecord::where('patient_id', $validated['patient_id'])
            ->orderByDesc('created_on')
            ->first();

        if ($currentRecord) {
            $currentRecord->update([
                'diagnosis_id' => $diagnosisId,
                'details' => $validated['details'],
                'updated_by' => auth()->id(),
                'updated_on' => now(),
            ]);
        } else {
            MedicalRecord::create([
                'patient_id' => $validated['patient_id'],
                'diagnosis_id' => $diagnosisId,
                'details' => $validated['details'],
                'created_by' => auth()->id(),
                'created_on' => now(),
            ]);
        }

        $appointment->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('doctor.medical-records', ['patient_id' => $validated['patient_id']])
            ->with('success', $currentRecord ? 'Medical record updated successfully.' : 'Medical record added successfully.');
    }

    public function medicalRecords(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $departmentId = auth()->user()->staff->department_id;

        $showAll = $date === 'all';

        if (! $showAll) {
            try {
                $date = \Carbon\Carbon::parse($date)->toDateString();
            } catch (\Exception $e) {
                $date = now()->toDateString();
            }
        }

        $query = MedicalRecord::with(['patient', 'diagnosis', 'creator'])
            ->whereHas('creator', fn ($q) => $q->whereHas('staff', fn ($sq) => $sq->where('department_id', $departmentId)))
            ->orderBy('created_on', 'desc');

        if (! $showAll) {
            $query->whereDate('created_on', $date);
        }

        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%'.$search.'%']);
            });
        }

        $patientId = $request->query('patient_id');
        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        $records = $query->paginate(15);

        return view('doctor.medical-records', [
            'records' => $records,
            'patientId' => $patientId,
            'date' => $date,
            'search' => $search,
        ]);
    }
}
