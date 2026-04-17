<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $departmentId = auth()->user()->staff->department_id;

        $appointments = Appointment::with(['patient', 'service'])
            ->where('schedule', $date)
            ->whereHas('service', fn($q) => $q->where('department_id', $departmentId))
            ->orderByDesc('queue_number')
            ->orderBy('schedule_time')
            ->paginate(15);

        $totalCount = Appointment::where('schedule', $date)
            ->whereHas('service', fn($q) => $q->where('department_id', $departmentId))
            ->count();

        $statusCounts = Appointment::where('schedule', $date)
            ->whereHas('service', fn($q) => $q->where('department_id', $departmentId))
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $services = \App\Models\Service::where('department_id', $departmentId)->where('is_active', true)->get();

        return view('staff.dashboard', [
            'date' => $date,
            'appointments' => $appointments,
            'totalCount' => $totalCount,
            'statusCounts' => $statusCounts,
            'services' => $services,
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Prevent staff from changing status once the doctor has completed the appointment
        if ($appointment->status === 'completed') {
            return redirect()
                ->route('staff.dashboard', ['date' => $appointment->schedule])
                ->withErrors(['status' => 'This appointment has been completed by the doctor and can no longer be updated.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:not started,started,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        broadcast(new QueueUpdated($appointment->queue_number))->toOthers();

        return redirect()
            ->route('staff.dashboard', ['date' => $appointment->schedule])
            ->with('success', 'Appointment status updated successfully.');
    }

    public function updateDetails(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'weight'         => 'nullable|numeric|min:0|max:999',
            'height'         => 'nullable|numeric|min:0|max:999',
            'blood_pressure' => 'nullable|string|max:20',
            'temperature'    => 'nullable|numeric|min:25|max:45',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('staff.dashboard', ['date' => $appointment->schedule])
            ->with('success', 'Patient details updated successfully.');
    }
    public function updateService(Request $request, Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return redirect()
                ->route('staff.dashboard', ['date' => $appointment->schedule])
                ->withErrors(['service' => 'This appointment has been completed and the service can no longer be updated.']);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $appointment->update([
            'service_id' => $validated['service_id'],
        ]);

        return redirect()
            ->route('staff.dashboard', ['date' => $appointment->schedule])
            ->with('success', 'Appointment service updated successfully.');
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

        return view('staff.medical-records', [
            'records' => $records,
            'patientId' => $patientId,
            'date' => $date,
            'search' => $search,
        ]);
    }
}
