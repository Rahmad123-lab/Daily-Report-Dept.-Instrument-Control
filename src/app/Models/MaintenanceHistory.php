<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
    'equipment_id',
    'work_order_number',
    'maintenance_type',
    'status',
    'maintenance_date',
    'technician',
    'duration',
    'result',
    'notes',
    'created_by',
];

    protected $casts = [
        'maintenance_date' => 'date',
        'duration' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(
            Equipment::class,
            'equipment_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}