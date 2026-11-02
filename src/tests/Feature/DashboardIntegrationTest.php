<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(
        string $role = 'karyawan',
        string $status = 'active'
    ): User {
        return User::factory()->create([
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function test_admin_can_view_integrated_dashboard(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Operational Overview')
            ->assertSee('Daily Report Overview')
            ->assertSee('Equipment Status Overview')
            ->assertSee('Training Overview')
            ->assertSee('Competency Development')
            ->assertSee('Daily Report Trend')
            ->assertSee('Recent Daily Reports');
    }

    public function test_active_employee_can_view_dashboard(): void
    {
        $employee = $this->makeUser('karyawan');

        $this->actingAs($employee)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Operational Overview')
            ->assertSee('Competency Development')
            ->assertSee('Training Recommendations')
            ->assertDontSee('Pending Employee Accounts');
    }

    public function test_employee_dashboard_only_shows_own_competency_priority(): void
    {
        $employeeA = $this->makeUser('karyawan');
        $employeeB = $this->makeUser('karyawan');

        $competencyA = Competency::create([
            'code' => 'TEST-DASH-001',
            'name' => 'Employee A PLC Competency',
            'category' => 'Instrumentation',
            'description' => 'Employee A competency.',
            'is_active' => true,
        ]);

        $competencyB = Competency::create([
            'code' => 'TEST-DASH-002',
            'name' => 'Employee B DCS Competency',
            'category' => 'Automation',
            'description' => 'Employee B competency.',
            'is_active' => true,
        ]);

        EmployeeCompetency::create([
            'user_id' => $employeeA->id,
            'competency_id' => $competencyA->id,
            'current_level' => 1,
            'target_level' => 4,
            'assessed_at' => now()->toDateString(),
        ]);

        EmployeeCompetency::create([
            'user_id' => $employeeB->id,
            'competency_id' => $competencyB->id,
            'current_level' => 2,
            'target_level' => 5,
            'assessed_at' => now()->toDateString(),
        ]);

        $this->actingAs($employeeA)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Employee A PLC Competency')
            ->assertDontSee('Employee B DCS Competency');
    }

    public function test_admin_dashboard_includes_active_employee_competency_gaps(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = Competency::create([
            'code' => 'TEST-DASH-003',
            'name' => 'Admin Dashboard PLC Analysis',
            'category' => 'Instrumentation',
            'description' => 'Dashboard integration test.',
            'is_active' => true,
        ]);

        EmployeeCompetency::create([
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => 2,
            'target_level' => 4,
            'assessed_at' => now()->toDateString(),
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Admin Dashboard PLC Analysis')
            ->assertSee('Total Gap Levels');
    }
}
