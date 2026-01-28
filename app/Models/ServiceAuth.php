<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAuth extends Model
{
    protected $fillable = [
        'service_id',
        'role_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function service(){
        return $this->hasMany(Service::class);
    }

    public function role(){
        return $this->hasMany(Role::class);
    }
}
