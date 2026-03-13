<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\RhuDashboardController;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/home', [Controller::class, 'index'])->name('home');

Route::get('/appointments/create', [PatientController::class, 'create'])->name('appointment.create');
Route::post('/appointments', [PatientController::class, 'storePatient'])->name('appointment.storePatient');
Route::post('/appointments/{id}/start', [PatientController::class, 'start'])
    ->name('appointments.start');

Route::get('/queue-board', function () {
    $today = now()->toDateString();

    $nowServing = Appointment::with(['patient', 'service'])
        ->where('schedule', $today)
        ->where('status', 'started')
        ->orderBy('queue_number')
        ->first();

    $upcoming = Appointment::with(['patient', 'service'])
        ->where('schedule', $today)
        ->where('status', 'not started')
        ->orderBy('queue_number')
        ->take(5)
        ->get();

    return view('queue-board', [
        'nowServing' => $nowServing,
        'upcoming'   => $upcoming,
    ]);
});

// API endpoint for queue board polling
Route::get('/api/queue-board', function () {
    $today = now()->toDateString();

    $nowServing = Appointment::with(['patient', 'service'])
        ->where('schedule', $today)
        ->where('status', 'started')
        ->orderBy('queue_number')
        ->first();

    $upcoming = Appointment::with(['patient', 'service'])
        ->where('schedule', $today)
        ->where('status', 'not started')
        ->orderBy('queue_number')
        ->take(5)
        ->get();

    return response()->json([
        'now_serving' => $nowServing ? [
            'queue_number' => $nowServing->queue_number,
            'patient_name' => $nowServing->patient
                ? $nowServing->patient->first_name . ' ' . $nowServing->patient->last_name
                : 'N/A',
            'service' => optional($nowServing->service)->name ?? 'N/A',
        ] : null,
        'upcoming' => $upcoming->map(fn($a) => [
            'queue_number' => $a->queue_number,
            'patient_name' => $a->patient
                ? $a->patient->first_name . ' ' . $a->patient->last_name
                : 'N/A',
            'service' => optional($a->service)->name ?? 'N/A',
        ]),
    ]);
});

// Staff authentication
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

// Staff-only routes
Route::middleware(['staff', 'dashboard.no-cache'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::patch('/staff/appointments/{appointment}/status', [StaffDashboardController::class, 'updateStatus'])
        ->name('staff.appointments.updateStatus');

    // RHU Dashboard (Admin Only)
    Route::middleware(['admin'])->group(function () {
        Route::get('/rhu/dashboard', [RhuDashboardController::class, 'index'])
            ->name('rhu.dashboard');
    });
});

// Doctor-only routes (staff + doctor role/position)
Route::middleware(['staff', 'doctor', 'dashboard.no-cache'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/patients/{patient}/add-record', [DoctorDashboardController::class, 'addRecord'])->name('patients.add-record');
    Route::post('/medical-records', [DoctorDashboardController::class, 'storeRecord'])->name('medical-records.store');
    Route::get('/medical-records', [DoctorDashboardController::class, 'medicalRecords'])->name('medical-records');
});

