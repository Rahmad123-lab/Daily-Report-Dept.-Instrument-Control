<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    protected $fillable = [
        'title',
        'category',
        'organizer',
        'instructor',
        'location',
        'start_date',
        'end_date',
        'quota',
        'status',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'quota' => 'integer',
        ];
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
 * Competencies covered by this training.
 */
public function competencies(): BelongsToMany
{
    return $this->belongsToMany(
        Competency::class,
        'competency_training'
    )->withTimestamps();
}
}