<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispensingLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'medicine_id',
        'medicine_name',
        'quantity_dispensed',
        'unit',
        'dispensed_by',
        'dispensed_at',
    ];

    protected function casts(): array
    {
        return [
            'dispensed_at' => 'datetime',
        ];
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function dispenser()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }
}
