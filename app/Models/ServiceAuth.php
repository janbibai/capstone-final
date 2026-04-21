<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ServiceAuth
 *
 * @property int $id
 * @property int $service_id
 * @property int $role_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Service $service
 * @property-read \App\Models\Role $role
 */
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
        return $this->belongsTo(Service::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }   
}
