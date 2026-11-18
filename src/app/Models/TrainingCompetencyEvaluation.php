<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingCompetencyEvaluation extends Model
{
    protected $fillable = [
        'training_participant_id',
        'employee_competency_id',
        'user_id',
        'competency_id',
        'assessed_by',

        'training_title',
        'employee_name',
        'competency_code',
        'competency_name',
        'competency_category',

        'pre_training_level',
        'post_training_level',
        'target_level',

        'assessed_at',
        'evidence',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'pre_training_level' => 'integer',
            'post_training_level' => 'integer',
            'target_level' => 'integer',
            'assessed_at' => 'date',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(TrainingParticipant::class, 'training_participant_id');
    }

    public function employeeCompetency(): BelongsTo
    {
        return $this->belongsTo(EmployeeCompetency::class);
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
