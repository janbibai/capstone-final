<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'manufacturing_date',
        'quantity',
        'unit',
        'expiry_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Batches of stock for this medicine.
     */
    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }

    /**
     * Sync the parent medicine's quantity and expiry_date
     * from the sum / earliest-expiry of its batches.
     */
    public function syncStockFromBatches(): void
    {
        $batches = $this->batches()->where('quantity', '>', 0)->get();

        $this->update([
            'quantity'    => $batches->sum('quantity'),
            'expiry_date' => $batches
                ->whereNotNull('expiry_date')
                ->sortBy('expiry_date')
                ->first()?->expiry_date,
        ]);
    }
}
