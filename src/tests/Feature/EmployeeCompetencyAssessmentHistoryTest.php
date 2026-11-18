<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetencyAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeCompetencyAssessmentHistoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an active test user.
     */
    private function makeUser(string $role = 'karyawan'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => 'active',
        ]);
    }

    /**
     * Create a competency master record.
     */
    private function makeCompetency(
        string $code,
        string $name,
        string $category
    ): Competency {
        return Competency::create([
            'code' => $code,
            'name' => $name,
            'category' => $category,
            'description' => 'Test competency description.',
            'is_active' => true,
        ]);
    }

    /**
     * Create an assessment history snapshot.
     */
    private function makeHistory(
        User $employee,
        Competency $competency,
        array $attributes = []
    ): EmployeeCompetencyAssessment {
        return EmployeeCompetencyAssessment::create(array_merge([
            'employee_competency_id' => null,
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'assessed_by' => null,

            'employee_name' => $employee->name,
            'assessor_name' => 'Instrument Supervisor',

            'competency_code' => $competency->code,
            'competency_name' => $competency->name,
            'competency_category' => $competency->category,

            'current_level' => 2,
            'target_level' => 4,
            'assessed_at' => '2026-11-08',

            'evidence' => 'Practical assessment evidence.',
            'notes' => 'Assessment test notes.',
        ], $attributes));
    }

    /**
     * Admin can view assessment histories from all employees.
     */
    public function test_admin_can_view_all_assessment_histories(): void
    {
        $admin = $this->makeUser('admin');

        $employeeOne = $this->makeUser();
        $employeeTwo = $this->makeUser();

        $plc = $this->makeCompetency(
            'INST-PLC-HISTORY',
            'PLC Troubleshooting History',
            'Instrumentation'
        );

        $dcs = $this->makeCompetency(
            'INST-DCS-HISTORY',
            'DCS Troubleshooting History',
            'Automation'
        );

        $this->makeHistory($employeeOne, $plc);
        $this->makeHistory($employeeTwo, $dcs);

        $this->actingAs($admin)
            ->get(route('employee-competencies.history'))
            ->assertOk()
            ->assertSeeText($employeeOne->name)
            ->assertSeeText($employeeTwo->name)
            ->assertSeeText('PLC Troubleshooting History')
            ->assertSeeText('DCS Troubleshooting History');
    }

    /**
     * Employees can only view their own assessment histories.
     */
    public function test_employee_can_only_view_own_assessment_history(): void
    {
        $employeeOne = $this->makeUser();
        $employeeTwo = $this->makeUser();

        $plc = $this->makeCompetency(
            'INST-OWN-HISTORY',
            'Own PLC Assessment',
            'Instrumentation'
        );

        $dcs = $this->makeCompetency(
            'INST-OTHER-HISTORY',
            'Other Employee DCS Assessment',
            'Automation'
        );

        $this->makeHistory($employeeOne, $plc);
        $this->makeHistory($employeeTwo, $dcs);

        // Attempt to request another employee's history.
        $url = route('employee-competencies.history')
            . '?user_id=' . $employeeTwo->id;

        $this->actingAs($employeeOne)
            ->get($url)
            ->assertOk()
            ->assertSeeText('Own PLC Assessment')
            ->assertDontSeeText('Other Employee DCS Assessment');
    }

    /**
     * Admin can filter histories by employee and category.
     */
    public function test_admin_can_filter_history_by_employee_and_category(): void
    {
        $admin = $this->makeUser('admin');

        $employeeOne = $this->makeUser();
        $employeeTwo = $this->makeUser();

        $plc = $this->makeCompetency(
            'INST-FILTER-PLC',
            'PLC Instrumentation Filter',
            'Instrumentation'
        );

        $dcs = $this->makeCompetency(
            'INST-FILTER-DCS',
            'DCS Automation Filter',
            'Automation'
        );

        $pressure = $this->makeCompetency(
            'INST-FILTER-PRESS',
            'Pressure Transmitter Filter',
            'Instrumentation'
        );

        $this->makeHistory($employeeOne, $plc);
        $this->makeHistory($employeeOne, $dcs);
        $this->makeHistory($employeeTwo, $pressure);

        $this->actingAs($admin)
            ->get(route('employee-competencies.history', [
                'user_id' => $employeeOne->id,
                'category' => 'Instrumentation',
            ]))
            ->assertOk()
            ->assertSeeText('PLC Instrumentation Filter')
            ->assertDontSeeText('DCS Automation Filter')
            ->assertDontSeeText('Pressure Transmitter Filter');
    }

    /**
     * Search can find competency and assessor snapshots.
     */
    public function test_history_search_finds_competency_and_assessor(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency(
            'INST-SEARCH-001',
            'Advanced PLC Search Test',
            'Instrumentation'
        );

        $this->makeHistory($employee, $competency, [
            'assessor_name' => 'Rahmad Instrument Supervisor',
        ]);

        // Search by competency name.
        $this->actingAs($admin)
            ->get(route('employee-competencies.history', [
                'search' => 'Advanced PLC Search Test',
            ]))
            ->assertOk()
            ->assertSeeText('Advanced PLC Search Test');

        // Search by assessor snapshot.
        $this->actingAs($admin)
            ->get(route('employee-competencies.history', [
                'search' => 'Rahmad Instrument Supervisor',
            ]))
            ->assertOk()
            ->assertSeeText('Advanced PLC Search Test');
    }

    /**
     * Assessment history can be filtered by date range.
     */
    public function test_history_can_be_filtered_by_assessment_date_range(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency(
            'INST-DATE-001',
            'Date Filter Competency',
            'Instrumentation'
        );

        $this->makeHistory($employee, $competency, [
            'assessed_at' => '2026-10-30',
            'notes' => 'October assessment record.',
        ]);

        $this->makeHistory($employee, $competency, [
            'assessed_at' => '2026-11-08',
            'notes' => 'November assessment record.',
        ]);

        $this->makeHistory($employee, $competency, [
            'assessed_at' => '2026-12-01',
            'notes' => 'December assessment record.',
        ]);

        $this->actingAs($admin)
            ->get(route('employee-competencies.history', [
                'from_date' => '2026-11-01',
                'to_date' => '2026-11-30',
            ]))
            ->assertOk()
            ->assertSeeText('November assessment record.')
            ->assertDontSeeText('October assessment record.')
            ->assertDontSeeText('December assessment record.');
    }

    /**
     * Invalid date range must be rejected.
     */
    public function test_invalid_history_date_range_is_rejected(): void
    {
        $admin = $this->makeUser('admin');

        $url = route('employee-competencies.history', [
            'from_date' => '2026-11-30',
            'to_date' => '2026-11-01',
        ]);

        $this->actingAs($admin)
            ->from(route('employee-competencies.history'))
            ->get($url)
            ->assertRedirect(route('employee-competencies.history'))
            ->assertSessionHasErrors('to_date');
    }

    /**
     * Unauthenticated users must be redirected to login.
     */
    public function test_guest_cannot_view_assessment_history(): void
    {
        $this->get(route('employee-competencies.history'))
            ->assertRedirect(route('login'));
    }

    /**
     * History pagination must use 15 records per page.
     */
    public function test_assessment_history_uses_pagination(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency(
            'INST-PAGINATION-001',
            'Pagination Competency',
            'Instrumentation'
        );

        for ($i = 1; $i <= 16; $i++) {
            $this->makeHistory($employee, $competency, [
                'notes' => "Pagination record {$i}.",
                'assessed_at' => '2026-11-08',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('employee-competencies.history'))
            ->assertOk()
            ->assertViewHas('assessmentHistories', function ($histories) {
                return $histories->total() === 16
                    && $histories->perPage() === 15
                    && $histories->count() === 15;
            });
    }
}
