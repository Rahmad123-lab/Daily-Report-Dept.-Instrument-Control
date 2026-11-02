<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingRecommendationTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'karyawan'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function makeCompetency(array $attributes = []): Competency
    {
        return Competency::create(array_merge([
            'code' => 'INST-PLC-001',
            'name' => 'PLC Troubleshooting',
            'category' => 'Instrumentation',
            'description' => 'PLC troubleshooting competency.',
            'is_active' => true,
        ], $attributes));
    }

    private function makeTraining(array $attributes = []): Training
    {
        return Training::create(array_merge([
            'title' => 'Advanced PLC Training',
            'category' => 'Automation',
            'organizer' => 'Instrument Department',
            'instructor' => 'Senior Engineer',
            'location' => 'Power Plant Workshop',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'quota' => 10,
            'status' => 'planned',
            'description' => 'PLC training.',
            'created_by' => null,
        ], $attributes));
    }

    private function makeAssessment(
        User $employee,
        Competency $competency,
        array $attributes = []
    ): EmployeeCompetency {
        return EmployeeCompetency::create(array_merge([
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => 1,
            'target_level' => 4,
            'assessed_by' => null,
            'assessed_at' => now()->toDateString(),
            'evidence' => null,
            'notes' => null,
        ], $attributes));
    }

    public function test_employee_can_view_own_training_recommendations(): void
    {
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $training = $this->makeTraining();

        $this->makeAssessment($employee, $competency);

        $competency->trainings()->attach($training->id);

        $this->actingAs($employee)
            ->get(route('training-recommendations.index'))
            ->assertOk()
            ->assertSee('Training Recommendations')
            ->assertSee($competency->name)
            ->assertSee($training->title)
            ->assertSee('Gap 3 Levels');
    }

    public function test_employee_cannot_see_other_employee_recommendations(): void
    {
        $employeeA = $this->makeUser();
        $employeeB = $this->makeUser();

        $competencyA = $this->makeCompetency([
            'code' => 'INST-PLC-A',
            'name' => 'Employee A PLC Competency',
        ]);

        $competencyB = $this->makeCompetency([
            'code' => 'INST-DCS-B',
            'name' => 'Employee B DCS Competency',
        ]);

        $trainingA = $this->makeTraining([
            'title' => 'Employee A Training',
        ]);

        $trainingB = $this->makeTraining([
            'title' => 'Employee B Training',
        ]);

        $this->makeAssessment($employeeA, $competencyA);
        $this->makeAssessment($employeeB, $competencyB);

        $competencyA->trainings()->attach($trainingA->id);
        $competencyB->trainings()->attach($trainingB->id);

        $this->actingAs($employeeA)
            ->get(route('training-recommendations.index'))
            ->assertOk()
            ->assertSee($competencyA->name)
            ->assertSee($trainingA->title)
            ->assertDontSee($competencyB->name)
            ->assertDontSee($trainingB->title);
    }

    public function test_admin_can_view_recommendations_for_active_employees(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();
        $training = $this->makeTraining();

        $this->makeAssessment($employee, $competency);

        $competency->trainings()->attach($training->id);

        $this->actingAs($admin)
            ->get(route('training-recommendations.index'))
            ->assertOk()
            ->assertSee($employee->name)
            ->assertSee($competency->name)
            ->assertSee($training->title);
    }

    public function test_completed_training_is_not_recommended(): void
    {
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();

        $training = $this->makeTraining([
            'title' => 'Completed PLC Training',
            'status' => 'completed',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->subDays(4)->toDateString(),
        ]);

        $this->makeAssessment($employee, $competency);

        $competency->trainings()->attach($training->id);

        $this->actingAs($employee)
            ->get(route('training-recommendations.index'))
            ->assertOk()
            ->assertSee($competency->name)
            ->assertDontSee($training->title)
            ->assertSee('No mapped training available');
    }

    public function test_unassessed_competency_does_not_generate_recommendation(): void
    {
        $employee = $this->makeUser();

        $competency = $this->makeCompetency();
        $training = $this->makeTraining();

        $this->makeAssessment($employee, $competency, [
            'current_level' => null,
            'assessed_by' => null,
            'assessed_at' => null,
        ]);

        $competency->trainings()->attach($training->id);

        $this->actingAs($employee)
            ->get(route('training-recommendations.index'))
            ->assertOk()
            ->assertSee('No Competency Gaps Found')
            ->assertDontSee($training->title);
    }
}
