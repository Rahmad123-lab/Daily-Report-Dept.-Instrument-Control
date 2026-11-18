<?php

namespace App\Console\Commands;

use App\Models\EmployeeCompetency;
use App\Models\EmployeeCompetencyAssessment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BackfillEmployeeCompetencyAssessmentHistory extends Command
{
    /**
     * Command signature.
     */
    protected $signature = 'competencies:backfill-assessment-history
                            {--dry-run : Preview records without inserting them}';

    /**
     * Command description.
     */
    protected $description = 'Create baseline assessment history for existing employee competencies';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $eligible = 0;
        $created = 0;
        $skipped = 0;

        $this->info('Starting competency assessment history backfill...');

        if ($dryRun) {
            $this->warn('DRY RUN MODE: No database records will be created.');
        }

        EmployeeCompetency::query()
            ->whereNotNull('current_level')
            ->with([
                'employee:id,name',
                'competency:id,code,name,category',
                'assessor:id,name',
            ])
            ->chunkById(100, function ($records) use (
                &$eligible,
                &$created,
                &$skipped,
                $dryRun
            ): void {
                // Get history records that already exist for this batch.
                $existingIds = EmployeeCompetencyAssessment::query()
                    ->whereIn('employee_competency_id', $records->pluck('id'))
                    ->pluck('employee_competency_id')
                    ->all();

                $existingLookup = array_fill_keys($existingIds, true);

                foreach ($records as $record) {
                    // Do not create duplicate baseline history.
                    if (isset($existingLookup[$record->id])) {
                        $skipped++;
                        continue;
                    }

                    $eligible++;

                    if ($dryRun) {
                        continue;
                    }

                    // Use assessed_at, or fall back to the existing record date.
                    $assessedAt = $record->assessed_at
                        ? Carbon::parse($record->assessed_at)->toDateString()
                        : (
                            $record->updated_at
                            ?? $record->created_at
                            ?? now()
                        )->toDateString();

                    EmployeeCompetencyAssessment::create([
                        'employee_competency_id' => $record->id,

                        'user_id' => $record->user_id,
                        'competency_id' => $record->competency_id,
                        'assessed_by' => $record->assessed_by,

                        // Snapshot data.
                        'employee_name' => $record->employee?->name
                            ?? 'Unknown Employee',

                        'assessor_name' => $record->assessor?->name
                            ?? 'Unknown',

                        'competency_code' => $record->competency?->code
                            ?? 'UNKNOWN',

                        'competency_name' => $record->competency?->name
                            ?? 'Unknown Competency',

                        'competency_category' => $record->competency?->category,

                        // Assessment values.
                        'current_level' => $record->current_level,
                        'target_level' => $record->target_level,
                        'assessed_at' => $assessedAt,
                        'evidence' => $record->evidence,
                        'notes' => $record->notes,
                    ]);

                    $created++;
                }
            });

        $this->newLine();

        $this->table(
            ['Result', 'Count'],
            [
                ['Eligible records', $eligible],
                ['Created records', $created],
                ['Skipped existing history', $skipped],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Preview completed. No records were inserted.'
            );
        } else {
            $this->info(
                'Assessment history backfill completed successfully.'
            );
        }

        return self::SUCCESS;
    }
}
