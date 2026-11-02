<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
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
}
