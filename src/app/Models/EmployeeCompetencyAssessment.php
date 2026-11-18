<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCompetencyAssessment extends Model
{
  protected $fillable = [
    'employee_competency_id',

    // Identitas karyawan
    'user_id',
    'employee_name',

    // Identitas kompetensi
    'competency_id',
    'competency_code',
    'competency_name',
    'competency_category',

    // Hasil assessment
    'current_level',
    'target_level',

    // Informasi penilaian
    'assessed_at',
    'assessed_by',
    'assessor_name',

    // Bukti dan catatan
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