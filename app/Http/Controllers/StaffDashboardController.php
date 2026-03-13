<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Models\Appointment;
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

        return view('staff.dashboard', [
            'date' => $date,
            'appointments' => $appointments,
            'totalCount' => $totalCount,
            'statusCounts' => $statusCounts,
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
}
