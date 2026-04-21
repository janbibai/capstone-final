<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $queue_number
 */
class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'service_id',
        'schedule',
        'schedule_time',
        'queue_number',
        'status',
        'weight',
        'height',
        'blood_pressure',
        'temperature',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
