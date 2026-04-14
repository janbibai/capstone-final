<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'medical_record_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
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
}
