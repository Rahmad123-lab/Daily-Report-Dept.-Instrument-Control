<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\EmployeeCompetency;
use App\Models\EmployeeCompetencyAssessment;
use App\Models\Training;
use App\Models\TrainingCompetencyEvaluation;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TrainingCompetencyEvaluationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mengatur waktu untuk pengujian.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(
            Carbon::parse('2026-11-12 12:00:00')
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /**
     * Membuat user untuk pengujian.
     */
    private function makeUser(string $role = 'karyawan'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => 'active',
        ]);
    }

    /**
     * Membuat training untuk pengujian.
     */
    private function makeTraining(array $attributes = []): Training
    {
        return Training::create(array_merge([
            'title' => 'PLC Troubleshooting Training',
            'category' => 'Instrumentation & Control',
            'organizer' => 'Instrument & Control Department',
            'instructor' => 'Senior Instrument Engineer',
            'location' => 'Power Plant Workshop',
            'start_date' => '2026-11-08',
            'end_date' => '2026-11-09',
            'quota' => 10,
            'status' => 'completed',
            'description' => 'Training competency evaluation test.',
            'created_by' => null,
        ], $attributes));
    }

    /**
     * Membuat master kompetensi.
     */
    private function makeCompetency(array $attributes = []): Competency
    {
        static $sequence = 0;

        $sequence++;

        return Competency::create(array_merge([
            'code' => 'INST-EVAL-' . $sequence,
            'name' => 'PLC Troubleshooting ' . $sequence,
            'category' => 'Instrumentation & Control',
            'description' => 'Competency for evaluation testing.',
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Membuat peserta training.
     */
    private function makeParticipant(
        Training $training,
        User $employee,
        array $attributes = []
    ): TrainingParticipant {
        return $training->participants()->create(array_merge([
            'user_id' => $employee->id,
            'registration_status' => 'approved',
            'attendance_status' => 'present',
            'notes' => null,
        ], $attributes));
    }

    /**
     * Membuat penugasan kompetensi karyawan.
     */
    private function makeEmployeeCompetency(
        User $employee,
        Competency $competency,
        array $attributes = []
    ): EmployeeCompetency {
        return EmployeeCompetency::create(array_merge([
            'user_id' => $employee->id,
            'competency_id' => $competency->id,
            'current_level' => 3,
            'target_level' => 5,
            'assessed_by' => null,
            'assessed_at' => '2026-11-07',
            'evidence' => 'Latest current evidence.',
            'notes' => 'Latest current assessment.',
        ], $attributes));
    }

    /**
     * Membuat riwayat assessment sebelumnya.
     */
    private function makeAssessmentHistory(
        User $employee,
        Competency $competency,
        EmployeeCompetency $assignment,
        array $attributes = []
    ): EmployeeCompetencyAssessment {
        return EmployeeCompetencyAssessment::create(array_merge([
            'employee_competency_id' => $assignment->id,

            'user_id' => $employee->id,
            'employee_name' => $employee->name,

            'competency_id' => $competency->id,
            'competency_code' => $competency->code,
            'competency_name' => $competency->name,
            'competency_category' => $competency->category,

            'current_level' => 2,
            'target_level' => 5,

            'assessed_at' => '2026-11-07',
            'assessed_by' => null,
            'assessor_name' => 'Previous Assessor',

            'evidence' => 'Baseline evidence.',
            'notes' => 'Baseline assessment.',
        ], $attributes));
    }

    /**
     * Payload evaluasi.
     */
    private function evaluationPayload(
        Competency $competency,
        array $attributes = []
    ): array {
        return array_merge([
            'competency_id' => $competency->id,
            'post_training_level' => 4,
            'assessed_at' => '2026-11-10',
            'evidence' => 'Practical post-training assessment.',
            'notes' => 'Employee demonstrated improved troubleshooting.',
        ], $attributes);
    }

    /**
     * URL untuk menyimpan evaluasi.
     */
    private function evaluationUrl(
        Training $training,
        TrainingParticipant $participant
    ): string {
        return route(
            'trainings.participants.evaluations.store',
            [
                'training' => $training->id,
                'participant' => $participant->id,
            ]
        );
    }

    /**
     * Test 1:
     * Admin dapat membuka form evaluasi.
     */
    public function test_admin_can_open_evaluation_form_for_eligible_participant(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();
        $participant = $this->makeParticipant($training, $employee);

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency($employee, $competency);

        $training->competencies()->attach($competency->id);

        $this->actingAs($admin)
            ->get(route(
                'trainings.participants.evaluations.create',
                [
                    'training' => $training->id,
                    'participant' => $participant->id,
                ]
            ))
            ->assertOk()
            ->assertViewHas('competencies', function ($competencies) use ($competency) {
                return $competencies->contains('id', $competency->id);
            });
    }

    /**
     * Test 2:
     * User non-admin tidak boleh melakukan evaluasi.
     */
    public function test_non_admin_cannot_open_or_submit_training_evaluation(): void
    {
        $employee = $this->makeUser();
        $participantUser = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $participantUser
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency($participantUser, $competency);

        $training->competencies()->attach($competency->id);

        $this->actingAs($employee)
            ->get(route(
                'trainings.participants.evaluations.create',
                [
                    'training' => $training->id,
                    'participant' => $participant->id,
                ]
            ))
            ->assertForbidden();

        $this->actingAs($employee)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertForbidden();

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );
    }

    /**
     * Test 3:
     * Training harus berstatus completed.
     */
    public function test_evaluation_is_rejected_when_training_is_not_completed(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining([
            'status' => 'in_progress',
        ]);

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency($employee, $competency);

        $training->competencies()->attach($competency->id);

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );
    }

    /**
     * Test 4:
     * Peserta harus approved dan present.
     */
    public function test_evaluation_is_rejected_when_participant_is_not_approved_or_present(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency($employee, $competency);

        $training->competencies()->attach($competency->id);

        // Kasus registrasi belum approved.
        $notApproved = $this->makeParticipant(
            $training,
            $employee,
            [
                'registration_status' => 'registered',
            ]
        );

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $notApproved),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        // Kasus peserta tidak hadir.
        $absentEmployee = $this->makeUser();

        $absentParticipant = $this->makeParticipant(
            $training,
            $absentEmployee,
            [
                'attendance_status' => 'absent',
            ]
        );

        $this->makeEmployeeCompetency(
            $absentEmployee,
            $competency
        );

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $absentParticipant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );
    }

    /**
     * Test 5:
     * Evaluasi berhasil disimpan ke seluruh tabel.
     */
    public function test_successful_evaluation_updates_current_level_and_both_histories(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $assignment = $this->makeEmployeeCompetency(
            $employee,
            $competency,
            [
                'current_level' => 3,
                'target_level' => 5,
                'assessed_at' => '2026-11-07',
            ]
        );

        $training->competencies()->attach($competency->id);

        // Baseline sebelum training dimulai.
        $this->makeAssessmentHistory(
            $employee,
            $competency,
            $assignment,
            [
                'current_level' => 2,
                'assessed_at' => '2026-11-07',
                'notes' => 'Actual pre-training baseline.',
            ]
        );

        // Riwayat setelah training dimulai tidak boleh menjadi baseline.
        $this->makeAssessmentHistory(
            $employee,
            $competency,
            $assignment,
            [
                'current_level' => 3,
                'assessed_at' => '2026-11-09',
                'notes' => 'Assessment after training started.',
            ]
        );

        $this->actingAs($admin)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('success');

        // ============================================================
// 1. Periksa snapshot evaluasi training
// ============================================================

$this->assertDatabaseHas(
    'training_competency_evaluations',
    [
        'training_participant_id' => $participant->id,
        'employee_competency_id' => $assignment->id,
        'user_id' => $employee->id,
        'competency_id' => $competency->id,
        'assessed_by' => $admin->id,
        'employee_name' => $employee->name,
        'competency_code' => $competency->code,
        'competency_name' => $competency->name,
        'pre_training_level' => 2,
        'post_training_level' => 4,
        'target_level' => 5,
    ]
);

// Periksa tanggal menggunakan normalisasi Carbon.
$evaluation = \DB::table('training_competency_evaluations')
    ->where('training_participant_id', $participant->id)
    ->where('competency_id', $competency->id)
    ->first();

$this->assertNotNull($evaluation);

$this->assertSame(
    '2026-11-10',
    Carbon::parse($evaluation->assessed_at)->toDateString()
);


// ============================================================
// 2. Periksa pembaruan employee_competencies
// ============================================================

$this->assertDatabaseHas(
    'employee_competencies',
    [
        'id' => $assignment->id,
        'current_level' => 4,
        'target_level' => 5,
        'assessed_by' => $admin->id,
    ]
);

// Periksa tanggal secara terpisah.
$updatedAssignment = \DB::table('employee_competencies')
    ->where('id', $assignment->id)
    ->first();

$this->assertNotNull($updatedAssignment);

$this->assertSame(
    '2026-11-10',
    Carbon::parse($updatedAssignment->assessed_at)->toDateString()
);


// ============================================================
// 3. Periksa riwayat assessment
// ============================================================

// Dua riwayat baseline + satu riwayat evaluasi baru.
$this->assertDatabaseCount(
    'employee_competency_assessments',
    3
);

$this->assertDatabaseHas(
    'employee_competency_assessments',
    [
        'employee_competency_id' => $assignment->id,
        'user_id' => $employee->id,
        'employee_name' => $employee->name,
        'competency_id' => $competency->id,
        'competency_code' => $competency->code,
        'competency_name' => $competency->name,
        'competency_category' => $competency->category,
        'assessed_by' => $admin->id,
        'assessor_name' => $admin->name,
        'current_level' => 4,
        'target_level' => 5,
        'evidence' => 'Practical post-training assessment.',
    ]
);

// Pastikan tanggal riwayat juga benar.
$assessmentHistory = \DB::table('employee_competency_assessments')
    ->where('employee_competency_id', $assignment->id)
    ->where('competency_id', $competency->id)
    ->where('current_level', 4)
    ->orderByDesc('id')
    ->first();

$this->assertNotNull($assessmentHistory);

$this->assertSame(
    '2026-11-10',
    Carbon::parse($assessmentHistory->assessed_at)->toDateString()
);
    }

    /**
     * Test 6:
     * Baseline mengambil assessment terakhir pada atau sebelum
     * tanggal mulai training.
     */
    public function test_latest_assessment_on_or_before_training_start_is_used_as_baseline(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $assignment = $this->makeEmployeeCompetency(
            $employee,
            $competency
        );

        $training->competencies()->attach($competency->id);

        $this->makeAssessmentHistory(
            $employee,
            $competency,
            $assignment,
            [
                'current_level' => 1,
                'assessed_at' => '2026-11-01',
            ]
        );

        $this->makeAssessmentHistory(
            $employee,
            $competency,
            $assignment,
            [
                'current_level' => 3,
                'assessed_at' => '2026-11-08',
                'notes' => 'Assessment dated on training start.',
            ]
        );

        $this->makeAssessmentHistory(
            $employee,
            $competency,
            $assignment,
            [
                'current_level' => 4,
                'assessed_at' => '2026-11-09',
                'notes' => 'Assessment after training began.',
            ]
        );

        $this->actingAs($admin)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('success');

        $this->assertDatabaseHas(
            'training_competency_evaluations',
            [
                'training_participant_id' => $participant->id,
                'competency_id' => $competency->id,
                'pre_training_level' => 3,
            ]
        );
    }

    /**
     * Test 7:
     * Evaluasi boleh disimpan walaupun belum memiliki baseline.
     */
    public function test_evaluation_can_be_saved_when_no_previous_baseline_exists(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency(
            $employee,
            $competency,
            [
                'current_level' => null,
                'assessed_by' => null,
                'assessed_at' => null,
            ]
        );

        $training->competencies()->attach($competency->id);

        $this->actingAs($admin)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('success');

        $this->assertDatabaseHas(
            'training_competency_evaluations',
            [
                'training_participant_id' => $participant->id,
                'competency_id' => $competency->id,
                'pre_training_level' => null,
                'post_training_level' => 4,
            ]
        );
    }

    /**
     * Test 8:
     * Evaluasi ganda harus ditolak.
     */
    public function test_duplicate_evaluation_is_rejected_without_adding_more_history(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $assignment = $this->makeEmployeeCompetency(
            $employee,
            $competency
        );

        $training->competencies()->attach($competency->id);

        // Evaluasi pertama.
        $this->actingAs($admin)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertSessionHas('success');

        // Evaluasi kedua harus ditolak.
        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload(
                    $competency,
                    [
                        'post_training_level' => 5,
                    ]
                )
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            1
        );

        $this->assertDatabaseCount(
            'employee_competency_assessments',
            1
        );

        $this->assertDatabaseHas(
            'employee_competencies',
            [
                'id' => $assignment->id,
                'current_level' => 4,
            ]
        );
    }

    /**
     * Test 9:
     * Kompetensi harus terhubung dengan training.
     */
    public function test_unmapped_competency_is_rejected(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency(
            $employee,
            $competency
        );

        // Kompetensi sengaja tidak dihubungkan ke training.

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );

        $this->assertDatabaseCount(
            'employee_competency_assessments',
            0
        );
    }

    /**
     * Test 10:
     * Kompetensi harus ditugaskan kepada peserta.
     */
    public function test_competency_must_be_assigned_to_participant(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $training->competencies()->attach($competency->id);

        // Kompetensi sengaja tidak ditugaskan kepada peserta.

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload($competency)
            )
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHasErrors('competency_id');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );

        $this->assertDatabaseCount(
            'employee_competency_assessments',
            0
        );
    }

    /**
     * Test 11:
     * Tanggal assessment harus setelah training selesai
     * dan tidak boleh berada di masa depan.
     */
    public function test_assessment_date_cannot_be_before_training_end_or_in_future(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency(
            $employee,
            $competency
        );

        $training->competencies()->attach($competency->id);

        $formUrl = route(
            'trainings.participants.evaluations.create',
            [
                'training' => $training->id,
                'participant' => $participant->id,
            ]
        );

        // Tanggal sebelum training selesai.
        $this->actingAs($admin)
            ->from($formUrl)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload(
                    $competency,
                    [
                        'assessed_at' => '2026-11-08',
                    ]
                )
            )
            ->assertRedirect($formUrl)
            ->assertSessionHasErrors('assessed_at');

        // Tanggal di masa depan.
        $this->actingAs($admin)
            ->from($formUrl)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload(
                    $competency,
                    [
                        'assessed_at' => '2026-11-13',
                    ]
                )
            )
            ->assertRedirect($formUrl)
            ->assertSessionHasErrors('assessed_at');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );
    }

    /**
     * Test 12:
     * Level kompetensi hanya boleh 1 sampai 5.
     */
    public function test_post_training_level_must_be_between_one_and_five(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competency = $this->makeCompetency();

        $this->makeEmployeeCompetency(
            $employee,
            $competency
        );

        $training->competencies()->attach($competency->id);

        $formUrl = route(
            'trainings.participants.evaluations.create',
            [
                'training' => $training->id,
                'participant' => $participant->id,
            ]
        );

        $this->actingAs($admin)
            ->from($formUrl)
            ->post(
                $this->evaluationUrl($training, $participant),
                $this->evaluationPayload(
                    $competency,
                    [
                        'post_training_level' => 6,
                    ]
                )
            )
            ->assertRedirect($formUrl)
            ->assertSessionHasErrors('post_training_level');

        $this->assertDatabaseCount(
            'training_competency_evaluations',
            0
        );
    }

    /**
     * Test 13:
     * Guest tidak boleh membuka form evaluasi.
     */
    public function test_guest_cannot_access_evaluation_form(): void
    {
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $this->get(route(
            'trainings.participants.evaluations.create',
            [
                'training' => $training->id,
                'participant' => $participant->id,
            ]
        ))
            ->assertRedirect(route('login'));
    }
}