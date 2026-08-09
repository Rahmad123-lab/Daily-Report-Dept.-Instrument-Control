<?php

namespace App\Models;

use App\Models\MaintenanceHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [

        'equipment_code',

        'equipment_name',

        'tag_number',

        'category',

        'location',

        'manufacturer',

        'model',

        'serial_number',

        'installation_date',

        'status',

        'description',

    ];

    protected $casts = [

        'installation_date' => 'date',

    ];

    /**
     * Maintenance Histories
     */
    public function maintenanceHistories(): HasMany
    {
        return $this->hasMany(
            MaintenanceHistory::class,
            'equipment_id'
        );
    }
}