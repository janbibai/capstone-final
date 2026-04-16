<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyDashboardController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\RhuDashboardController;
use App\Models\Appointment;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/home', [Controller::class, 'index'])->name('home');

Route::get('/appointments/create', [PatientController::class, 'create'])->name('appointment.create');
Route::get('/appointments/queue-status', [PatientController::class, 'queueStatus'])->name('appointment.queueStatus');
Route::get('/appointments/queue-status-data', [PatientController::class, 'getQueueStatusData'])->name('appointment.queueStatusData');
Route::get('/appointments/booked-times', [PatientController::class, 'getBookedTimes'])->name('appointment.bookedTimes');
Route::post('/appointments', [PatientController::class, 'storePatient'])->name('appointment.storePatient');
Route::post('/appointments/{id}/start', [PatientController::class, 'start'])
    ->name('appointments.start');

Route::get('/queue-board', function () {
    return view('queue-board');
});

Route::get('/queue-stream', function () {
    $response = new StreamedResponse(function () {
        $latestAppointment = Appointment::where('schedule', now()->toDateString())
            ->where('status', 'started')
            ->orderByDesc('updated_at')
            ->first();

        $currentQueueNumber = $latestAppointment ? $latestAppointment->queue_number : 0;

        // Set the retry interval for the browser to auto-reconnect (2000ms = 2 seconds)
        echo "retry: 2000\n";
        echo "data: " . json_encode(['queue_number' => $currentQueueNumber]) . "\n\n";
        
        ob_flush();
        flush();
    });

    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('Connection', 'close'); // Close connection after sending so it doesn't block the PHP server

    return $response;
});

// Staff authentication
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::get('/staff/register', [StaffAuthController::class, 'showRegisterForm'])->name('staff.register');
Route::post('/staff/register', [StaffAuthController::class, 'register'])->name('staff.register.submit');
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

// Staff-only routes
Route::middleware(['staff', 'dashboard.no-cache'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::patch('/staff/appointments/{appointment}/status', [StaffDashboardController::class, 'updateStatus'])
        ->name('staff.appointments.updateStatus');

    Route::patch('/staff/appointments/{appointment}/details', [StaffDashboardController::class, 'updateDetails'])
        ->name('staff.appointments.updateDetails');

    // RHU Dashboard (Admin Only)
    Route::middleware(['admin'])->group(function () {
        Route::get('/rhu/dashboard', [RhuDashboardController::class, 'index'])
            ->name('rhu.dashboard');
        Route::get('/rhu/dashboard/diagnosis-patients', [RhuDashboardController::class, 'diagnosisPatients'])
            ->name('rhu.diagnosisPatients');
        Route::patch('/rhu/staff/{staff}/approve', [RhuDashboardController::class, 'approveStaff'])
            ->name('rhu.staff.approve');
        Route::delete('/rhu/staff/{staff}/reject', [RhuDashboardController::class, 'rejectStaff'])
            ->name('rhu.staff.reject');

        // Medicine Inventory
        Route::post('/rhu/medicines', [RhuDashboardController::class, 'storeMedicine'])
            ->name('rhu.medicines.store');
        Route::put('/rhu/medicines/{medicine}', [RhuDashboardController::class, 'updateMedicine'])
            ->name('rhu.medicines.update');
        Route::delete('/rhu/medicines/{medicine}', [RhuDashboardController::class, 'deleteMedicine'])
            ->name('rhu.medicines.delete');
        Route::patch('/rhu/medicines/{medicine}/add-stock', [RhuDashboardController::class, 'addStock'])
            ->name('rhu.medicines.addStock');
        Route::delete('/rhu/medicine-batches/{batch}', [RhuDashboardController::class, 'deleteBatch'])
            ->name('rhu.batches.delete');
    });
});

// Doctor-only routes (staff + doctor role/position)
Route::middleware(['staff', 'doctor', 'dashboard.no-cache'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/patients/{patient}/add-record', [DoctorDashboardController::class, 'addRecord'])->name('patients.add-record');
    Route::post('/medical-records', [DoctorDashboardController::class, 'storeRecord'])->name('medical-records.store');
    Route::get('/medical-records', [DoctorDashboardController::class, 'medicalRecords'])->name('medical-records');
});

// Pharmacy-only routes (staff + pharmacy role/position)
Route::middleware(['staff', 'pharmacy', 'dashboard.no-cache'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dashboard', [PharmacyDashboardController::class, 'index'])->name('dashboard');
    Route::post('/medicines/{medicine}/dispense', [PharmacyDashboardController::class, 'dispense'])->name('medicines.dispense');
});

