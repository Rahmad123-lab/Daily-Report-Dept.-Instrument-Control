<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function employeeCompetencies(): HasMany
    {
        return $this->hasMany(EmployeeCompetency::class);
    }
    /**
 * Training programs associated with this competency.
 */
public function trainings(): BelongsToMany
{
    return $this->belongsToMany(
        Training::class,
        'competency_training'
    )->withTimestamps();
}
}