<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DispensingLog;

class Prescription extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'medical_record_id',
        'medication_name',
        'generic_name',
        'type',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'instructions',
        'created_on',
    ];

    protected function casts(): array
    {
        return [
            'created_on' => 'datetime',
        ];
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function dispensingLogs()
    {
        return $this->hasMany(DispensingLog::class);
    }
}
