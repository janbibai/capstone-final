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

    public function serviceAuths()
    {
        return $this->hasMany(ServiceAuth::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'services_auth');
    }
}
