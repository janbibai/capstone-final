<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DispensingLog;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

        // ── Chart Data ─────────────────────────────────────────────────

        // 1) Appointments per month (last 12 months)
        $appointmentsPerMonth = DB::table('appointments')
            ->select(
                DB::raw("DATE_FORMAT(schedule, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('schedule', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 2) Patients per department (unique patients via appointments → services)
        $patientsPerDepartment = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('departments', 'services.department_id', '=', 'departments.id')
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT appointments.patient_id) as patient_count')
            )
            ->where('departments.is_active', true)
            ->groupBy('departments.id', 'departments.name')
            ->orderBy('departments.name')
            ->get();

        // 3) Top diagnoses this month
        $topDiagnosesThisMonth = DB::table('medical_records')
            ->join('diagnoses', 'medical_records.diagnosis_id', '=', 'diagnoses.id')
            ->select(
                'diagnoses.name as diagnosis_name',
                DB::raw('COUNT(medical_records.id) as total_count')
            )
            ->where('medical_records.created_on', '>=', Carbon::now()->startOfMonth())
            ->groupBy('diagnoses.id', 'diagnoses.name')
            ->orderByDesc('total_count')
            ->limit(10)
            ->get();

        // ── Overview KPIs ────────────────────────────────────────────────
        $totalPatients = DB::table('patients')->count();
        $appointmentsToday = DB::table('appointments')->whereDate('schedule', Carbon::today())->count();
        $completedToday = DB::table('appointments')->whereDate('schedule', Carbon::today())->where('status', 'completed')->count();
        $pendingToday = DB::table('appointments')->whereDate('schedule', Carbon::today())->where('status', 'not started')->count();
        $activeDepartments = DB::table('departments')->where('is_active', true)->count();
        $diagnosesRecorded = DB::table('medical_records')->whereBetween('created_on', [$startDate, $endDate])->count();

        // ── Staff Accounts ───────────────────────────────
        $staffAccounts = Staff::with(['user', 'department'])
            ->orderByDesc('created_at')
            ->get();

        // ── Medicine Inventory (with batches) ─────────────────────────
        $medicines = Medicine::with(['batches' => function ($q) {
            $q->orderBy('expiry_date', 'asc');
        }])->orderBy('name')->get();

        // ── Medicine Dispensing Statistics ─────────────────────────────
        $topDispensedMedicines = DB::table('dispensing_logs')
            ->select(
                'medicine_name',
                'unit',
                DB::raw('SUM(quantity_dispensed) as total_dispensed'),
                DB::raw('COUNT(*) as dispense_count')
            )
            ->whereBetween('dispensed_at', [$startDate, $endDate])
            ->groupBy('medicine_name', 'unit')
            ->orderByDesc('total_dispensed')
            ->limit(10)
            ->get();

        // ── Tabular Chronological Dispensing Logs ──────────────────────
        $dispensingLogsTabular = DispensingLog::with([
                'prescription.medicalRecord.patient',
                'dispenser'
            ])
            ->whereBetween('dispensed_at', [$startDate, $endDate])
            ->orderByDesc('dispensed_at')
            ->get();

            // ── Departments (for create-account form) ─────────────────
            $departments = Department::where('is_active', true)->orderBy('name')->get();

            return view('rhu.dashboard', [
                'groupedStatistics' => $groupedStatistics,
                'topDiseases' => $topDiseases,
                'filter' => $filter,
                'appointmentsPerMonth' => $appointmentsPerMonth,
                'patientsPerDepartment' => $patientsPerDepartment,
                'topDiagnosesThisMonth' => $topDiagnosesThisMonth,
                // Overview KPIs
                'totalPatients' => $totalPatients,
                'appointmentsToday' => $appointmentsToday,
                'completedToday' => $completedToday,
                'pendingToday' => $pendingToday,
                'activeDepartments' => $activeDepartments,
                'diagnosesRecorded' => $diagnosesRecorded,
                // Staff accounts
                'staffAccounts' => $staffAccounts,
                // Medicine inventory
                'medicines' => $medicines,
                // Medicine dispensing statistics
                'topDispensedMedicines' => $topDispensedMedicines,
                'dispensingLogsTabular' => $dispensingLogsTabular,
                // Departments
                'departments' => $departments,
            ]);
    }

    /**
     * AJAX: Return patients for a given diagnosis within a department.
     */
    public function diagnosisPatients(Request $request)
    {
        $filter        = $request->query('filter', 'today');
        $diagnosisName = $request->query('diagnosis_name');
        $departmentName = $request->query('department_name');

        $startDate = match ($filter) {
            'week'  => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year'  => Carbon::now()->startOfYear(),
            default => Carbon::today(),
        };
        $endDate = Carbon::now()->endOfDay();

        $patients = DB::table('medical_records')
            ->join('diagnoses', 'medical_records.diagnosis_id', '=', 'diagnoses.id')
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->join('users', 'medical_records.created_by', '=', 'users.id')
            ->join('staff', 'users.id', '=', 'staff.user_id')
            ->join('departments', 'staff.department_id', '=', 'departments.id')
            ->select(
                'patients.id',
                'patients.first_name',
                'patients.last_name',
                'patients.gender',
                'patients.date_of_birth',
                'medical_records.created_on'
            )
            ->where('diagnoses.name', $diagnosisName)
            ->where('departments.name', $departmentName)
            ->whereBetween('medical_records.created_on', [$startDate, $endDate])
            ->orderByDesc('medical_records.created_on')
            ->get();

        return response()->json($patients);
    }

    /**
     * Approve a pending staff registration.
     */
    public function approveStaff(Staff $staff)
    {
        $staff->update(['is_active' => true]);

        return back()->with('success', $staff->user->name . ' has been approved.');
    }

    /**
     * Reject (delete) a pending staff registration.
     */
    public function rejectStaff(Staff $staff)
    {
        $userName = $staff->user->name;
        $user = $staff->user;

        // Delete the staff record and its associated user account
        $staff->delete();
        $user->delete();

        return back()->with('success', $userName . '\'s registration has been rejected.');
    }

    /**
     * Store a new medicine.
     */
    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:100'],
            'quantity'     => ['required', 'integer', 'min:0'],
            'unit'         => ['required', 'string', 'max:50'],
            'expiry_date'  => ['nullable', 'date'],
            'description'  => ['nullable', 'string', 'max:500'],
        ]);

        $medicine = Medicine::create($request->only([
            'name', 'generic_name', 'category', 'quantity', 'unit', 'expiry_date', 'description',
        ]));

        // Create the initial batch
        if ($medicine->quantity > 0 || $medicine->expiry_date) {
            $medicine->batches()->create([
                'quantity'    => $medicine->quantity,
                'unit'        => $medicine->unit,
                'expiry_date' => $medicine->expiry_date,
            ]);
        }

        return back()->with('success', 'Medicine "' . $request->name . '" has been added to the inventory.');
    }

    /**
     * Update an existing medicine (name, generic, category, unit, description only).
     */
    public function updateMedicine(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:100'],
            'unit'         => ['required', 'string', 'max:50'],
            'description'  => ['nullable', 'string', 'max:500'],
        ]);

        $medicine->update($request->only([
            'name', 'generic_name', 'category', 'unit', 'description',
        ]));

        return back()->with('success', 'Medicine "' . $medicine->name . '" has been updated.');
    }

    /**
     * Add stock to an existing medicine by creating a new batch.
     */
    public function addStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'add_quantity' => ['required', 'integer', 'min:1'],
            'unit'         => ['required', 'string', 'max:50'],
            'expiry_date'  => ['nullable', 'date'],
        ]);

        $medicine->batches()->create([
            'quantity'    => $request->add_quantity,
            'unit'        => $request->unit,
            'expiry_date' => $request->expiry_date,
        ]);

        $medicine->syncStockFromBatches();

        return back()->with('success', $request->add_quantity . ' ' . $request->unit . ' of "' . $medicine->name . '" added to stock. New total: ' . $medicine->quantity . '.');
    }

    /**
     * Delete a specific medicine batch (e.g. expired batches).
     */
    public function deleteBatch(MedicineBatch $batch)
    {
        $medicine = $batch->medicine;
        $batchInfo = $batch->quantity . ' ' . $medicine->unit;

        $batch->delete();
        $medicine->syncStockFromBatches();

        return back()->with('success', 'Batch (' . $batchInfo . ') of "' . $medicine->name . '" has been removed. Remaining stock: ' . $medicine->quantity . '.');
    }

    /**
     * Delete a medicine from inventory.
     */
    public function deleteMedicine(Medicine $medicine)
    {
        $name = $medicine->name;
        $medicine->delete();

        return back()->with('success', 'Medicine "' . $name . '" has been removed from inventory.');
    }

    /**
     * Create a new staff/doctor/pharmacy account directly (admin only).
     * The account is created as active — no approval step needed.
     */
    public function createStaff(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'position'      => ['required', 'in:Staff,Doctor,Pharmacy'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone'         => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Try to match a role by position name
            $role = Role::where('name', $request->position)->first();

            // Auto-generate employee ID
            $latestStaff = Staff::orderByDesc('id')->first();
            $nextNumber  = $latestStaff ? ($latestStaff->id + 1) : 1;
            $employeeId  = 'EMP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $staffData = [
                'user_id'       => $user->id,
                'department_id' => $request->department_id,
                'employee_id'   => $employeeId,
                'position'      => $request->position,
                'phone'         => $request->phone,
                'is_active'     => true,  // admin-created accounts are active immediately
            ];

            if (Schema::hasColumn('staff', 'role_id') && $role) {
                $staffData['role_id'] = $role->id;
            }

            Staff::create($staffData);
        });

        return back()->with('success', 'Account for "' . $request->name . '" (' . $request->position . ') has been created successfully.');
    }
}
