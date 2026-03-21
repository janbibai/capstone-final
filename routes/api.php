<?php

use App\Http\Controllers\Api\ApiPatientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Patient API Routes (Public — No Auth Required)
|--------------------------------------------------------------------------
|
| These endpoints are consumed by the Flutter mobile app for patients
| to browse services, check available times, book appointments,
| and monitor queue status.
|
*/

// List active services
Route::get('/services', [ApiPatientController::class, 'getServices']);

// Get booked time slots for a date
Route::get('/appointments/booked-times', [ApiPatientController::class, 'getBookedTimes']);

// Book an appointment (registers patient + creates appointment)
Route::post('/appointments', [ApiPatientController::class, 'storeAppointment']);

// Get queue status for a date
Route::get('/appointments/queue-status', [ApiPatientController::class, 'getQueueStatus']);

// SSE stream for real-time queue updates
Route::get('/queue-stream', [ApiPatientController::class, 'queueStream']);
