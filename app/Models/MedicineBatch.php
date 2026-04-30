<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id',
        'quantity',
        'unit',
        'manufacturing_date',
        'expiry_date',
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * The medicine this batch belongs to.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
