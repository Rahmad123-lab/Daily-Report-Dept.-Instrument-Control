<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\EmployeeCompetencyAssessment;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetencyManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an active test user.
     */
    private function makeUser(
        string $role = 'karyawan',
        string $status = 'active'
    ): User {
        return User::factory()->create([
            'role' => $role,
            'status' => $status,
        ]);
    }

    /**
     * Create a competency master record.
     */
    private function makeCompetency(
        array $attributes = []
    ): Competency {
        return Competency::create(array_merge([
            'code' => 'INST-PLC-001',
            'name' => 'PLC Troubleshooting',
            'category' => 'Instrumentation & Control',
            'description' => 'PLC troubleshooting competency.',
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Create an employee competency record.
     */
    private function makeAssessment(
        User $employee,
        Competency $competency,
        array $attributes = []
    ): EmployeeCompetency {
        return EmployeeCompetency::create(array_merge([
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => 2,
            'target_level' => 4,
            'assessed_by' => null,
            'assessed_at' => '2026-10-30',
            'evidence' => 'Practical assessment.',
            'notes' => 'Test assessment.',
        ], $attributes));
    }

    /**
     * Admin can view competency master.
     */
    public function test_admin_can_view_competency_index(): void
    {
        $admin = $this->makeUser('admin');

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->get(route('competencies.index'))
            ->assertOk()
            ->assertSee($competency->name)
            ->assertSee($competency->code);
    }

    /**
     * Active employees can view active competencies.
     */
    public function test_active_user_can_view_active_competency(): void
    {
        $user = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->actingAs($user)
            ->get(route('competencies.show', $competency))
            ->assertOk()
            ->assertSee($competency->name);
    }

    /**
     * Non-admin cannot access inactive competency details.
     */
    public function test_non_admin_cannot_view_inactive_competency(): void
    {
        $user = $this->makeUser();

        $competency = $this->makeCompetency([
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->get(route('competencies.show', $competency))
            ->assertNotFound();
    }
public function test_competency_linked_to_training_cannot_be_deleted(): void
{
    $admin = $this->makeUser('admin');
    $competency = $this->makeCompetency();

    $training = Training::create([
        'title' => 'PLC Troubleshooting Training',
        'start_date' => '2026-11-10',
        'end_date' => '2026-11-11',
        'status' => 'planned',
    ]);

    $training->competencies()->attach($competency->id);

    $this->actingAs($admin)
        ->from(route('competencies.index'))
        ->delete(route('competencies.destroy', $competency))
        ->assertRedirect(route('competencies.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('competencies', [
        'id' => $competency->id,
    ]);

    $this->assertDatabaseHas('competency_training', [
        'competency_id' => $competency->id,
        'training_id' => $training->id,
    ]);
}
    /**
     * Admin can create a new competency.
     */
    public function test_admin_can_create_competency(): void
    {
        $admin = $this->makeUser('admin');

        $payload = [
            'code' => 'INST-DCS-001',
            'name' => 'DCS Troubleshooting',
            'category' => 'Automation & Control',
            'description' => 'DCS troubleshooting and diagnostics.',
            'is_active' => '1',
        ];

        $this->actingAs($admin)
            ->post(route('competencies.store'), $payload)
            ->assertRedirect(route('competencies.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('competencies', [
            'code' => 'INST-DCS-001',
            'name' => 'DCS Troubleshooting',
            'is_active' => 1,
        ]);
    }

    /**
     * Non-admin cannot create competency.
     */
    public function test_non_admin_cannot_create_competency(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post(route('competencies.store'), [
                'code' => 'INST-TEST-001',
                'name' => 'Unauthorized Competency',
                'category' => 'Instrumentation',
                'is_active' => '1',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('competencies', [
            'code' => 'INST-TEST-001',
        ]);
    }

    /**
     * Competency code must be unique.
     */
    public function test_duplicate_competency_code_is_rejected(): void
    {
        $admin = $this->makeUser('admin');

        $this->makeCompetency([
            'code' => 'INST-PLC-001',
        ]);

        $this->actingAs($admin)
            ->from(route('competencies.create'))
            ->post(route('competencies.store'), [
                'code' => 'INST-PLC-001',
                'name' => 'Another PLC Competency',
                'category' => 'Instrumentation',
                'is_active' => '1',
            ])
            ->assertRedirect(route('competencies.create'))
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('competencies', 1);
    }

    /**
     * Admin can update competency master.
     */
    public function test_admin_can_update_competency(): void
    {
        $admin = $this->makeUser('admin');

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->put(route('competencies.update', $competency), [
                'code' => $competency->code,
                'name' => 'Advanced PLC Troubleshooting',
                'category' => 'Automation & Control',
                'description' => 'Advanced PLC diagnostics.',
                'is_active' => '1',
            ])
            ->assertRedirect(route('competencies.show', $competency))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('competencies', [
            'id' => $competency->id,
            'name' => 'Advanced PLC Troubleshooting',
        ]);
    }

    /**
     * A competency assigned to employees cannot be deleted.
     */
    public function test_used_competency_cannot_be_deleted(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->makeAssessment($employee, $competency);

        $this->actingAs($admin)
            ->from(route('competencies.index'))
            ->delete(route('competencies.destroy', $competency))
            ->assertRedirect(route('competencies.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('competencies', [
            'id' => $competency->id,
        ]);
    }

    /**
     * Admin can assign competency to an active employee.
     */
    public function test_admin_can_assign_competency_to_employee(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 2,
                'target_level' => 4,
                'assessed_at' => '2026-10-30',
                'evidence' => 'Practical PLC assessment.',
                'notes' => 'Needs advanced troubleshooting experience.',
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employee_competencies', [
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => 2,
            'target_level' => 4,
            'assessed_by' => $admin->id,
        ]);
    }

    /**
     * Assessment can be assigned without an initial current level.
     */
    public function test_admin_can_assign_unassessed_competency(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => '',
                'target_level' => 3,
                'assessed_at' => '',
                'evidence' => '',
                'notes' => 'Assessment pending.',
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employee_competencies', [
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => null,
            'target_level' => 3,
            'assessed_by' => null,
            'assessed_at' => null,
        ]);
    }

    /**
     * Current competency level must be between 1 and 5.
     */
    public function test_invalid_current_level_is_rejected(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->from(route('employee-competencies.index'))
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 6,
                'target_level' => 4,
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHasErrors('current_level');

        $this->assertDatabaseMissing('employee_competencies', [
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
        ]);
    }

    /**
     * Target competency level must be between 1 and 5.
     */
    public function test_invalid_target_level_is_rejected(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->from(route('employee-competencies.index'))
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 2,
                'target_level' => 0,
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHasErrors('target_level');
    }

    /**
     * Duplicate employee competency assignment is rejected.
     */
    public function test_duplicate_employee_competency_is_rejected(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $this->makeAssessment($employee, $competency);

        $this->actingAs($admin)
            ->from(route('employee-competencies.index'))
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 3,
                'target_level' => 4,
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount('employee_competencies', 1);
    }

    /**
     * Non-admin cannot assign competency to employees.
     */
    public function test_non_admin_cannot_assign_employee_competency(): void
    {
        $user = $this->makeUser();
        $employee = $this->makeUser();
        $competency = $this->makeCompetency();

        $this->actingAs($user)
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 2,
                'target_level' => 4,
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('employee_competencies', 0);
    }

    /**
     * Employees can only see their own competency assessments.
     */
    public function test_employee_can_only_view_own_competency_records(): void
    {
        $employeeA = $this->makeUser('karyawan');
        $employeeB = $this->makeUser('karyawan');

        $ownCompetency = $this->makeCompetency([
            'code' => 'INST-OWN-001',
            'name' => 'Own PLC Competency',
        ]);

        $otherCompetency = $this->makeCompetency([
            'code' => 'INST-OTHER-001',
            'name' => 'Other DCS Competency',
        ]);

        $this->makeAssessment($employeeA, $ownCompetency);
        $this->makeAssessment($employeeB, $otherCompetency);

        $this->actingAs($employeeA)
            ->get(route('employee-competencies.index'))
            ->assertOk()
            ->assertSee('Own PLC Competency')
            ->assertDontSee('Other DCS Competency');
    }

    /**
     * Non-admin cannot access competency gap dashboard.
     */
    public function test_non_admin_cannot_access_competency_dashboard(): void
    {
        $employee = $this->makeUser('karyawan');

        $this->actingAs($employee)
            ->get(route('competencies.dashboard'))
            ->assertForbidden();
    }

    /**
     * Admin can view competency gap dashboard.
     */
    public function test_admin_can_view_competency_gap_dashboard(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency([
            'category' => 'DCS',
        ]);

        $this->makeAssessment($employee, $competency, [
            'current_level' => 1,
            'target_level' => 2,
        ]);

        $this->actingAs($admin)
            ->get(route('competencies.dashboard'))
            ->assertOk()
            ->assertSee('Competency Gap Dashboard')
            ->assertSee('Total Assignments')
            ->assertSee('Below Target')
            ->assertSee('DCS');
    }

    public function test_competency_dashboard_excludes_inactive_competencies(): void
{
    $admin = $this->makeUser('admin');
    $employee = $this->makeUser();

    $activeCompetency = $this->makeCompetency([
        'code' => 'INST-ACTIVE-001',
        'name' => 'Active PLC Competency',
        'is_active' => true,
    ]);

    $inactiveCompetency = $this->makeCompetency([
        'code' => 'INST-INACTIVE-001',
        'name' => 'Inactive PLC Competency',
        'is_active' => false,
    ]);

    $this->makeAssessment($employee, $activeCompetency, [
        'current_level' => 2,
        'target_level' => 4,
    ]);

    $this->makeAssessment($employee, $inactiveCompetency, [
        'current_level' => 1,
        'target_level' => 5,
    ]);

    $this->actingAs($admin)
        ->get(route('competencies.dashboard'))
        ->assertOk()
        ->assertViewHas('totalAssignments', 1)
        ->assertViewHas('assessedCount', 1)
        ->assertViewHas('gapCount', 1)
        ->assertViewHas('totalGapLevels', 2);
}
public function test_competency_dashboard_shows_assessment_coverage_by_category(): void
{
    $admin = $this->makeUser('admin');
    $employee = $this->makeUser();

    $assessedCompetency = $this->makeCompetency([
        'code' => 'INST-COV-001',
        'name' => 'PLC Troubleshooting',
        'category' => 'Instrumentation',
    ]);

    $unassessedCompetency = $this->makeCompetency([
        'code' => 'INST-COV-002',
        'name' => 'DCS Troubleshooting',
        'category' => 'Instrumentation',
    ]);

    $this->makeAssessment($employee, $assessedCompetency, [
        'current_level' => 3,
        'target_level' => 4,
    ]);

    $this->makeAssessment($employee, $unassessedCompetency, [
        'current_level' => null,
        'target_level' => 3,
    ]);

    $this->actingAs($admin)
        ->get(route('competencies.dashboard'))
        ->assertOk()
        ->assertViewHas('coverageByCategory', function ($categories) {
            $category = $categories->firstWhere(
                'category',
                'Instrumentation'
            );

            return $category !== null
                && (int) $category->total_assignments === 2
                && (int) $category->assessed_count === 1
                && (float) $category->assessment_rate === 50.0;
        });
}
public function test_competency_dashboard_ranks_competencies_by_total_gap(): void
{
    $admin = $this->makeUser('admin');

    $employeeOne = $this->makeUser();
    $employeeTwo = $this->makeUser();

    $plc = $this->makeCompetency([
        'code' => 'INST-PLC-PRIORITY',
        'name' => 'PLC Troubleshooting',
    ]);

    $dcs = $this->makeCompetency([
        'code' => 'INST-DCS-PRIORITY',
        'name' => 'DCS Troubleshooting',
    ]);

    $this->makeAssessment($employeeOne, $plc, [
        'current_level' => 1,
        'target_level' => 5,
    ]);

    $this->makeAssessment($employeeTwo, $plc, [
        'current_level' => 2,
        'target_level' => 5,
    ]);

    $this->makeAssessment($employeeOne, $dcs, [
        'current_level' => 3,
        'target_level' => 5,
    ]);

    $this->actingAs($admin)
        ->get(route('competencies.dashboard'))
        ->assertOk()
        ->assertViewHas('priorityByCompetency', function ($priorities) use ($plc, $dcs) {
            $first = $priorities->first();

            $plcPriority = $priorities->firstWhere('id', $plc->id);
            $dcsPriority = $priorities->firstWhere('id', $dcs->id);

            return $first !== null
                && (int) $first->id === (int) $plc->id
                && (int) $plcPriority->affected_employees === 2
                && (int) $plcPriority->affected_records === 2
                && (int) $plcPriority->total_gap === 7
                && (float) $plcPriority->average_gap === 3.5
                && (int) $dcsPriority->total_gap === 2;
        });
}
    public function test_initial_assessment_creates_history_record(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();
        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 2,
                'target_level' => 4,
                'assessed_at' => '2026-11-08',
                'evidence' => 'Practical PLC assessment.',
                'notes' => 'Initial assessment.',
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employee_competency_assessments', [
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'assessed_by' => $admin->id,
            'employee_name' => $employee->name,
            'assessor_name' => $admin->name,
            'competency_code' => $competency->code,
            'current_level' => 2,
            'target_level' => 4,
            'evidence' => 'Practical PLC assessment.',
        ]);

        $history = EmployeeCompetencyAssessment::query()
            ->where('user_id', $employee->id)
            ->where('competency_id', $competency->id)
            ->firstOrFail();

        $this->assertSame('2026-11-08', $history->assessed_at->toDateString());
    }

    public function test_updating_assessment_preserves_previous_history(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();
        $competency = $this->makeCompetency();

        $this->actingAs($admin)
            ->post(route('employee-competencies.store'), [
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'current_level' => 2,
                'target_level' => 4,
                'assessed_at' => '2026-11-08',
                'evidence' => 'Initial evidence.',
                'notes' => 'Initial assessment.',
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHas('success');

        $record = EmployeeCompetency::query()
            ->where('user_id', $employee->id)
            ->where('competency_id', $competency->id)
            ->firstOrFail();

        $this->actingAs($admin)
            ->put(route('employee-competencies.update', $record), [
                'current_level' => 3,
                'target_level' => 4,
                'assessed_at' => '2026-12-08',
                'evidence' => 'Updated evidence.',
                'notes' => 'Improved competency.',
            ])
            ->assertRedirect(route('employee-competencies.index'))
            ->assertSessionHas('success');

        $history = EmployeeCompetencyAssessment::query()
            ->where('employee_competency_id', $record->id)
            ->orderBy('id')
            ->get();

        $this->assertCount(2, $history);

        // The first assessment must remain unchanged.
        $this->assertSame(2, $history[0]->current_level);
        $this->assertSame('2026-11-08', $history[0]->assessed_at->toDateString());
        $this->assertSame('Initial evidence.', $history[0]->evidence);

        // The update must create a second history entry.
        $this->assertSame(3, $history[1]->current_level);
        $this->assertSame('2026-12-08', $history[1]->assessed_at->toDateString());
        $this->assertSame('Updated evidence.', $history[1]->evidence);

        // The current assessment row should contain the latest values.
        $this->assertDatabaseHas('employee_competencies', [
            'id' => $record->id,
            'current_level' => 3,
            'target_level' => 4,
        ]);
    }

    
public function test_backfill_creates_history_and_does_not_duplicate_records(): void
{
    $admin = $this->makeUser('admin');
    $employee = $this->makeUser();
    $competency = $this->makeCompetency();

    $record = $this->makeAssessment($employee, $competency, [
        'assessed_by' => $admin->id,
        'assessed_at' => '2026-10-30',
        'current_level' => 2,
        'target_level' => 4,
    ]);

    // First execution creates the baseline history.
    $this->artisan('competencies:backfill-assessment-history')
        ->assertExitCode(0);

    $this->assertDatabaseCount('employee_competency_assessments', 1);

    $this->assertDatabaseHas('employee_competency_assessments', [
        'employee_competency_id' => $record->id,
        'user_id' => $employee->id,
        'competency_id' => $competency->id,
        'assessed_by' => $admin->id,
        'employee_name' => $employee->name,
        'assessor_name' => $admin->name,
        'competency_code' => $competency->code,
        'current_level' => 2,
        'target_level' => 4,
    ]);

    // Second execution must not create a duplicate.
    $this->artisan('competencies:backfill-assessment-history')
        ->assertExitCode(0);

    $this->assertDatabaseCount('employee_competency_assessments', 1);
}

public function test_backfill_skips_unassessed_competencies(): void
{
    $employee = $this->makeUser();
    $competency = $this->makeCompetency();

    $this->makeAssessment($employee, $competency, [
        'current_level' => null,
        'target_level' => 3,
        'assessed_by' => null,
        'assessed_at' => null,
    ]);

    $this->artisan('competencies:backfill-assessment-history')
        ->assertExitCode(0);

    $this->assertDatabaseCount('employee_competency_assessments', 0);
}

}
