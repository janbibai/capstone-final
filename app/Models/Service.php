<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'code',
        'description',
        'estimated_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
