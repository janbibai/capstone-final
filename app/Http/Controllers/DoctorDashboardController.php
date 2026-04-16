<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $departmentId = auth()->user()->staff->department_id;

        $baseQuery = Appointment::where('schedule', $date)
            ->whereHas('service', fn ($q) => $q->where('department_id', $departmentId));

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'waiting' => (clone $baseQuery)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $appointments = (clone $baseQuery)->with(['patient', 'service'])
            ->orderBy('queue_number')
            ->orderBy('schedule_time')
            ->paginate(15);

        return view('doctor.dashboard', [
            'date' => $date,
            'appointments' => $appointments,
            'stats' => $stats,
        ]);
    }

    public function addRecord(Request $request, Patient $patient)
    {
        $appointmentId = $request->query('appointment_id');
        $recordId = $request->query('record_id');

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

        $forceNew = $request->query('new');

        if ($forceNew) {
            $currentRecord = null;
        } elseif ($recordId) {
            $currentRecord = MedicalRecord::with(['diagnosis', 'prescriptions'])
                ->where('id', $recordId)
                ->where('patient_id', $patient->id)
                ->first();
        } else {
            $currentRecord = MedicalRecord::with(['diagnosis', 'prescriptions'])
                ->where('patient_id', $patient->id)
                ->whereDate('created_on', now()->toDateString())
                ->first();
        }

        if (! $currentRecord && (! $appointment || ! in_array($appointment->status, ['started', 'completed']))) {
            return redirect()
                ->route('doctor.dashboard', ['date' => now()->toDateString()])
                ->withErrors(['appointment' => 'You can only add a diagnosis for a patient whose appointment has been started by staff.']);
        }

        $diagnoses = Diagnosis::orderBy('name')->get();

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
            'appointment_id' => 'nullable|exists:appointments,id',
            'record_id' => 'nullable|exists:medical_records,id',
            'diagnosis_id' => 'nullable|exists:diagnoses,id',
            'diagnosis_name' => 'nullable|string|max:255',
            'details' => 'required|string|max:1000',
            'prescriptions' => 'nullable|array',
            'prescriptions.*.medication_name' => 'required_with:prescriptions|string|max:255',
            'prescriptions.*.dosage' => 'nullable|string|max:255',
            'prescriptions.*.frequency' => 'nullable|string|max:255',
            'prescriptions.*.duration' => 'nullable|string|max:255',
            'prescriptions.*.instructions' => 'nullable|string|max:1000',
        ]);

        $currentRecord = null;
        if (!empty($validated['record_id'])) {
            $currentRecord = MedicalRecord::where('id', $validated['record_id'])
                ->where('patient_id', $validated['patient_id'])
                ->first();
        }

        $appointment = null;
        if (!empty($validated['appointment_id'])) {
            $appointment = Appointment::findOrFail($validated['appointment_id']);

            if ((int) $appointment->patient_id !== (int) $validated['patient_id']) {
                return back()
                    ->withErrors(['appointment_id' => 'The selected appointment does not belong to this patient.'])
                    ->withInput();
            }

            if (!in_array($appointment->status, ['started', 'completed'])) {
                return back()
                    ->withErrors(['appointment_id' => 'You can only add or update a diagnosis when the appointment status is "started" or "completed".'])
                    ->withInput();
            }
        } elseif (!$currentRecord) {
            return back()
                ->withErrors(['appointment_id' => 'An active appointment is required to add a new diagnosis.'])
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

        if ($currentRecord) {
            $currentRecord->update([
                'diagnosis_id' => $diagnosisId,
                'details' => $validated['details'],
                'updated_by' => auth()->id(),
                'updated_on' => now(),
            ]);
        } else {
            $currentRecord = MedicalRecord::create([
                'patient_id' => $validated['patient_id'],
                'diagnosis_id' => $diagnosisId,
                'details' => $validated['details'],
                'created_by' => auth()->id(),
                'created_on' => now(),
            ]);
        }

        // Sync prescriptions: delete old ones and insert new ones
        $currentRecord->prescriptions()->delete();

        $prescriptions = collect($validated['prescriptions'] ?? [])
            ->filter(fn ($p) => !empty(trim($p['medication_name'] ?? '')))
            ->values();

        foreach ($prescriptions as $p) {
            $currentRecord->prescriptions()->create([
                'medication_name' => $p['medication_name'],
                'dosage' => $p['dosage'] ?? null,
                'frequency' => $p['frequency'] ?? null,
                'duration' => $p['duration'] ?? null,
                'instructions' => $p['instructions'] ?? null,
                'created_on' => now(),
            ]);
        }

        if ($appointment) {
            $appointment->update([
                'status' => 'completed',
            ]);
        }

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

        $query = MedicalRecord::with(['patient', 'diagnosis', 'creator', 'prescriptions'])
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
