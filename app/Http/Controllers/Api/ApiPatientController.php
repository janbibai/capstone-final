<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientApiRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentService;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiPatientController extends Controller
{
    protected PatientService $patientService;
    protected AppointmentService $appointmentService;

    public function __construct(
        PatientService $patientService,
        AppointmentService $appointmentService
    ) {
        $this->patientService = $patientService;
        $this->appointmentService = $appointmentService;
    }

    /**
     * GET /api/services
     * Returns all active services.
     */
    public function getServices(): JsonResponse
    {
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'code', 'description', 'estimated_time', 'department_id')
            ->with('department:id,name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * GET /api/appointments/booked-times?date=YYYY-MM-DD
     * Returns already-booked time slots for a given date.
     */
    public function getBookedTimes(Request $request): JsonResponse
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $bookedTimes = Appointment::where('schedule', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('schedule_time')
            ->map(fn($time) => date('H:i', strtotime($time)))
            ->values();

        return response()->json([
            'success' => true,
            'data' => $bookedTimes,
        ]);
    }

    /**
     * POST /api/appointments
     * Register a patient and book an appointment.
     * Returns the queue number on success.
     */
    public function storeAppointment(StorePatientApiRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            // Register patient (pass uploaded ID file if present)
            $patient = $this->patientService->register($data, $request->file('valid_id'));

            // Create appointment
            $appointment = $this->appointmentService->schedule([
                'patient_id' => $patient->id,
                'service_id' => $data['service_id'],
                'schedule' => $data['schedule'],
                'schedule_time' => $data['schedule_time'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'data' => [
                    'appointment_id' => $appointment->id,
                    'queue_number' => $appointment->queue_number,
                    'queue_number_formatted' => 'Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT),
                    'schedule' => $appointment->schedule,
                    'schedule_time' => $appointment->schedule_time,
                    'patient_id' => $patient->id,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * GET /api/appointments/queue-status?date=YYYY-MM-DD
     * Returns queue status for a given date.
     */
    public function getQueueStatus(Request $request): JsonResponse
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json([
                'success' => true,
                'data' => [
                    'current_serving' => null,
                    'appointments' => [],
                ],
            ]);
        }

        $appointments = Appointment::with('patient')
            ->where('schedule', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('queue_number', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'queue_number' => $appointment->queue_number,
                    'queue_number_formatted' => 'Q-' . str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT),
                    'status' => $appointment->status,
                    'schedule_time' => date('h:i A', strtotime($appointment->schedule_time)),
                    'patient_name' => $appointment->patient->first_name . ' ' . mb_substr($appointment->patient->last_name, 0, 1) . '.',
                ];
            });

        $currentServing = $appointments->where('status', 'started')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'current_serving' => $currentServing,
                'appointments' => $appointments->values(),
            ],
        ]);
    }

    /**
     * GET /api/queue-stream
     * Server-Sent Events endpoint for real-time queue updates.
     */
    public function queueStream(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $latestAppointment = Appointment::where('schedule', now()->toDateString())
                ->where('status', 'started')
                ->orderByDesc('updated_at')
                ->first();

            $currentQueueNumber = $latestAppointment ? $latestAppointment->queue_number : 0;

            echo "retry: 2000\n";
            echo "data: " . json_encode(['queue_number' => $currentQueueNumber]) . "\n\n";

            ob_flush();
            flush();
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'close');

        return $response;
    }
}
