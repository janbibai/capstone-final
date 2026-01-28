<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function service_auth(){
        return $this->belongsTo(ServiceAuth::class);
    }
}
