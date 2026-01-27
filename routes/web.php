<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/user', [Controller::class, 'store']);
