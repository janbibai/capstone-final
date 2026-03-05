<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RhuDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'today');

        // Determine the date range based on filter
        $startDate = match ($filter) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::today(), // 'today'
        };
        $endDate = Carbon::now()->endOfDay();

        // Diagnosis counts per department (filtered by date)
        $statistics = DB::table('departments')
            ->join('staff', 'departments.id', '=', 'staff.department_id')
            ->join('users', 'staff.user_id', '=', 'users.id')
            ->join('medical_records', 'users.id', '=', 'medical_records.created_by')
            ->join('diagnoses', 'medical_records.diagnosis_id', '=', 'diagnoses.id')
            ->select(
                'departments.name as department_name',
                'diagnoses.name as diagnosis_name',
                DB::raw('COUNT(medical_records.id) as diagnosis_count')
            )
            ->where('departments.is_active', true)
            ->whereBetween('medical_records.created_on', [$startDate, $endDate])
            ->groupBy('departments.id', 'departments.name', 'diagnoses.id', 'diagnoses.name')
            ->orderBy('departments.name')
            ->orderByDesc('diagnosis_count')
            ->get();

        // Group by department
        $groupedStatistics = $statistics->groupBy('department_name');

        // Most common diseases overall (top 10, filtered by same date range)
        $topDiseases = DB::table('medical_records')
            ->join('diagnoses', 'medical_records.diagnosis_id', '=', 'diagnoses.id')
            ->select(
                'diagnoses.name as diagnosis_name',
                DB::raw('COUNT(medical_records.id) as total_count')
            )
            ->whereBetween('medical_records.created_on', [$startDate, $endDate])
            ->groupBy('diagnoses.id', 'diagnoses.name')
            ->orderByDesc('total_count')
            ->limit(10)
            ->get();

        return view('rhu.dashboard', [
            'groupedStatistics' => $groupedStatistics,
            'topDiseases' => $topDiseases,
            'filter' => $filter,
        ]);
    }
}
