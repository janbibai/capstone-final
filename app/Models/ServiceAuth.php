<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAuth extends Model
{
    protected $table = 'services_auth';

    protected $fillable = [
        'service_id',
        'role_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
