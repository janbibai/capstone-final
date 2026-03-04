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

        $appointments = Appointment::with(['patient', 'service'])
            ->where('schedule', $date)
            ->orderByDesc('queue_number')
            ->orderBy('schedule_time')
            ->get();

        $totalCount = Appointment::where('schedule', $date)->count();

        $statusCounts = Appointment::where('schedule', $date)
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

