<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCompetency extends Model
{
    protected $fillable = [
        'user_id',
        'competency_id',
        'current_level',
        'target_level',
        'assessed_by',
        'assessed_at',
        'evidence',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'current_level' => 'integer',
            'target_level' => 'integer',
            'assessed_at' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}