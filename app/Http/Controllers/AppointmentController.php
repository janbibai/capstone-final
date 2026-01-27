<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function stre(StoreAppointmentRequest $requests){

        Appointment::create([

        ]);
    }
}
