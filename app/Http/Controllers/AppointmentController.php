<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;
    
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    
    public function store(StoreAppointmentRequest $requests): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->schedule(
                $requests->validated()
            );

            return response()->json([
                'message'=> 'appointment scheduled success wow',
                'data'=> $appointment
            ], 201);
        } catch (\Exception $e){
            return response()->json([
                'message'=> $e->getMessage()
            ], 409);
        }
    }

     public function getByDate(string $date): JsonResponse 
     {
        return response()->json([
            'data'=> $this->appointmentService->getByDate($date)
        ]);
     }
    



}
