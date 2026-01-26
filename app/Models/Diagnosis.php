<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'created_on'
    ];
    protected $date = [
        'created_on'=> 'datetime'
     ];

    public function diagnosis_createdby()
    {
        return $this->belongsTo(User::class);
    }
}
