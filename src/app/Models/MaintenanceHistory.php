<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceHistory extends Model
{
    use HasFactory;

    /**
     * Mass Assignment
     */
    protected $fillable = [

        'equipment_id',

        'work_order_number',

        'maintenance_type',

        'maintenance_date',

        'technician',

        'duration',

        'result',

        'notes',

        'created_by',

    ];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'maintenance_date' => 'date',

        'duration' => 'integer',

    ];

    /**
     * Equipment Relationship
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Creator Relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}