<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class MedicalRecord extends Model
{
     protected $fillable = [
        'patient_id',
        'diagnosis_id',
        'details',
     ];
     protected $date = [
        'created_on'=> 'datetime'
     ];

    public function medical_records_createdby()
    {
        return $this->belongsTo(User::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
