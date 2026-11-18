<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingParticipant extends Model
{
    protected $fillable = [
        'training_id',
        'user_id',
        'registration_status',
        'attendance_status',
        'notes',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function competencyEvaluations(): HasMany
    {
        return $this->hasMany(
            TrainingCompetencyEvaluation::class,
            'training_participant_id'
        );
    }
}