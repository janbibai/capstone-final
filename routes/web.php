<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/appointments', [AppointmentController::class, 'store']);
// Route::get('/appointments/{date}', [AppointmentController::class, 'getByDate']);

// Route::post('/appointments', [AppointmentController::class, 'store']);
// Route::get('/appointments/{date}', [AppointmentController::class, 'byDate']);
// Route::post('/user', [Controller::class, 'store']);
