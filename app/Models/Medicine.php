<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'quantity',
        'unit',
        'expiry_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expiry_date' => 'date',
    ];
}
