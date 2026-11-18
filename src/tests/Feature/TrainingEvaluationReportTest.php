<?php

namespace Tests\Feature;

use App\Models\Competency;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TrainingEvaluationReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat user untuk testing.
     */
    private function makeUser(string $role = 'karyawan'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => 'active',
        ]);
    }

    /**
     * Membuat training.
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
            'description' => 'Training evaluation report test.',
            'created_by' => null,
        ], $attributes));
    }

    /**
     * Membuat peserta training.
     */
    private function makeParticipant(
        Training $training,
        User $employee
    ): TrainingParticipant {
        return $training->participants()->create([
            'user_id' => $employee->id,
            'registration_status' => 'approved',
            'attendance_status' => 'present',
            'notes' => null,
        ]);
    }

    /**
     * Membuat kompetensi.
     */
    private function makeCompetency(array $attributes = []): Competency
    {
        static $sequence = 0;
        $sequence++;

        return Competency::create(array_merge([
            'code' => 'INST-REPORT-' . $sequence,
            'name' => 'Report Competency ' . $sequence,
            'category' => 'Instrumentation',
            'description' => 'Competency for report testing.',
            'is_active' => true,
        ], $attributes));
    }

    /**
     * Menambahkan snapshot evaluasi untuk laporan.
     *
     * Tidak membuat EmployeeCompetency karena laporan
     * menggunakan snapshot training_competency_evaluations.
     */
    private function makeEvaluation(
        TrainingParticipant $participant,
        User $employee,
        User $admin,
        Competency $competency,
        array $attributes = []
    ): void {
        DB::table('training_competency_evaluations')->insert(
            array_merge([
                'training_participant_id' => $participant->id,
                'employee_competency_id' => null,
                'user_id' => $employee->id,
                'competency_id' => $competency->id,
                'assessed_by' => $admin->id,

                'training_title' => 'PLC Troubleshooting Training',
                'employee_name' => $employee->name,

                'competency_code' => $competency->code,
                'competency_name' => $competency->name,
                'competency_category' => $competency->category,

                'pre_training_level' => 2,
                'post_training_level' => 4,
                'target_level' => 5,

                'assessed_at' => '2026-11-10',
                'evidence' => 'Practical assessment evidence.',
                'notes' => 'Report testing notes.',

                'created_at' => now(),
                'updated_at' => now(),
            ], $attributes)
        );
    }

    /**
     * URL laporan training.
     */
    private function reportUrl(Training $training): string
    {
        return route(
            'trainings.evaluations.index',
            ['training' => $training->id]
        );
    }

    /**
     * Test 1:
     * Admin dapat membuka laporan evaluasi.
     */
    public function test_admin_can_open_training_evaluation_report(): void
    {
        $admin = $this->makeUser('admin');

        $training = $this->makeTraining();

        $response = $this->actingAs($admin)
            ->get($this->reportUrl($training));

        $response->assertOk();

        $response->assertViewIs('training-evaluations.index');

        $response->assertViewHas('training');

        $response->assertViewHas('evaluations');
    }

    /**
     * Test 2:
     * User non-admin tidak dapat membuka laporan.
     */
    public function test_non_admin_cannot_open_training_evaluation_report(): void
    {
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $this->actingAs($employee)
            ->get($this->reportUrl($training))
            ->assertForbidden();
    }

    /**
     * Test 3:
     * Guest diarahkan ke halaman login.
     */
    public function test_guest_cannot_open_training_evaluation_report(): void
    {
        $training = $this->makeTraining();

        $this->get($this->reportUrl($training))
            ->assertRedirect(route('login'));
    }

    /**
     * Test 4:
     * Ringkasan evaluasi dan target dihitung dengan benar.
     */
    public function test_report_calculates_evaluation_summary_correctly(): void
    {
        $admin = $this->makeUser('admin');

        $employeeOne = $this->makeUser();
        $employeeTwo = $this->makeUser();

        $training = $this->makeTraining();

        $participantOne = $this->makeParticipant(
            $training,
            $employeeOne
        );

        $participantTwo = $this->makeParticipant(
            $training,
            $employeeTwo
        );

        $competencyOne = $this->makeCompetency();
        $competencyTwo = $this->makeCompetency();

        // Employee 1: Target tercapai.
        $this->makeEvaluation(
            $participantOne,
            $employeeOne,
            $admin,
            $competencyOne,
            [
                'pre_training_level' => 1,
                'post_training_level' => 3,
                'target_level' => 3,
            ]
        );

        // Employee 1: Target belum tercapai.
        $this->makeEvaluation(
            $participantOne,
            $employeeOne,
            $admin,
            $competencyTwo,
            [
                'pre_training_level' => 2,
                'post_training_level' => 4,
                'target_level' => 5,
            ]
        );

        // Employee 2: Tidak memiliki baseline, target tercapai.
        $this->makeEvaluation(
            $participantTwo,
            $employeeTwo,
            $admin,
            $competencyOne,
            [
                'pre_training_level' => null,
                'post_training_level' => 2,
                'target_level' => 2,
            ]
        );

        $this->actingAs($admin)
            ->get($this->reportUrl($training))
            ->assertOk()
            ->assertViewHas(
                'totalEvaluations',
                3
            )
            ->assertViewHas(
                'totalEmployees',
                2
            )
            ->assertViewHas(
                'targetAchieved',
                2
            )
            ->assertViewHas(
                'targetNotAchieved',
                1
            )
            ->assertViewHas(
                'targetAchievementRate',
                66.7
            )
            ->assertViewHas('averagePostLevel', function ($average) {
                return round((float) $average, 1) === 3.0;
            });
    }

    /**
     * Test 5:
     * Laporan tetap tampil ketika belum ada evaluasi.
     */
    public function test_report_handles_empty_evaluations_correctly(): void
    {
        $admin = $this->makeUser('admin');

        $training = $this->makeTraining();

        $this->actingAs($admin)
            ->get($this->reportUrl($training))
            ->assertOk()
            ->assertViewHas('totalEvaluations', 0)
            ->assertViewHas('totalEmployees', 0)
            ->assertViewHas('averagePostLevel', null)
            ->assertViewHas('targetAchieved', 0)
            ->assertViewHas('targetNotAchieved', 0)
            ->assertViewHas('targetAchievementRate', 0);
    }

    /**
     * Test 6:
     * Pencarian berdasarkan nama karyawan.
     */
    public function test_report_can_search_by_employee_name(): void
    {
        $admin = $this->makeUser('admin');

        $employeeOne = $this->makeUser();
        $employeeTwo = $this->makeUser();

        $training = $this->makeTraining();

        $participantOne = $this->makeParticipant(
            $training,
            $employeeOne
        );

        $participantTwo = $this->makeParticipant(
            $training,
            $employeeTwo
        );

        $competencyOne = $this->makeCompetency();
        $competencyTwo = $this->makeCompetency();

        $this->makeEvaluation(
            $participantOne,
            $employeeOne,
            $admin,
            $competencyOne,
            [
                'employee_name' => 'Rahmad Joko',
            ]
        );

        $this->makeEvaluation(
            $participantTwo,
            $employeeTwo,
            $admin,
            $competencyTwo,
            [
                'employee_name' => 'Test Employee',
            ]
        );

        $this->actingAs($admin)
            ->get($this->reportUrl($training) . '?search=Rahmad')
            ->assertOk()
            ->assertViewHas('evaluations', function ($evaluations) {
                return $evaluations->total() === 1
                    && $evaluations->first()->employee_name === 'Rahmad Joko';
            });
    }

    /**
     * Test 7:
     * Pencarian berdasarkan nama dan kode kompetensi.
     */
    public function test_report_can_search_by_competency_name_and_code(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        $competencyOne = $this->makeCompetency([
            'code' => 'INST-PLC-001',
            'name' => 'PLC Troubleshooting',
        ]);

        $competencyTwo = $this->makeCompetency([
            'code' => 'INST-CAL-001',
            'name' => 'Instrument Calibration',
        ]);

        $this->makeEvaluation(
            $participant,
            $employee,
            $admin,
            $competencyOne
        );

        $this->makeEvaluation(
            $participant,
            $employee,
            $admin,
            $competencyTwo
        );

        // Pencarian berdasarkan nama kompetensi.
        $this->actingAs($admin)
            ->get($this->reportUrl($training) . '?search=Calibration')
            ->assertOk()
            ->assertViewHas('evaluations', function ($evaluations) {
                return $evaluations->total() === 1
                    && $evaluations->first()->competency_name
                        === 'Instrument Calibration';
            });

        // Pencarian berdasarkan kode kompetensi.
        $this->actingAs($admin)
            ->get($this->reportUrl($training) . '?search=INST-PLC-001')
            ->assertOk()
            ->assertViewHas('evaluations', function ($evaluations) {
                return $evaluations->total() === 1
                    && $evaluations->first()->competency_code
                        === 'INST-PLC-001';
            });
    }

    /**
     * Test 8:
     * Pagination menampilkan maksimal 15 data per halaman.
     */
    public function test_report_supports_pagination(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser();

        $training = $this->makeTraining();

        $participant = $this->makeParticipant(
            $training,
            $employee
        );

        for ($i = 1; $i <= 16; $i++) {
            $competency = $this->makeCompetency();

            $this->makeEvaluation(
                $participant,
                $employee,
                $admin,
                $competency
            );
        }

        // Halaman pertama.
        $this->actingAs($admin)
            ->get($this->reportUrl($training))
            ->assertOk()
            ->assertViewHas('evaluations', function ($evaluations) {
                return $evaluations->total() === 16
                    && $evaluations->count() === 15
                    && $evaluations->currentPage() === 1;
            });

        // Halaman kedua.
        $this->actingAs($admin)
            ->get($this->reportUrl($training) . '?page=2')
            ->assertOk()
            ->assertViewHas('evaluations', function ($evaluations) {
                return $evaluations->total() === 16
                    && $evaluations->count() === 1
                    && $evaluations->currentPage() === 2;
            });
    }

    /**
 * Test 9:
 * Laporan dapat difilter berdasarkan kategori kompetensi.
 */
public function test_report_can_filter_by_competency_category(): void
{
    $admin = $this->makeUser('admin');
    $employee = $this->makeUser();

    $training = $this->makeTraining();

    $participant = $this->makeParticipant(
        $training,
        $employee
    );

    $plcCompetency = $this->makeCompetency([
        'code' => 'INST-PLC-FILTER',
        'name' => 'PLC Troubleshooting',
        'category' => 'PLC',
    ]);

    $instrumentCompetency = $this->makeCompetency([
        'code' => 'INST-CAL-FILTER',
        'name' => 'Instrument Calibration',
        'category' => 'Instrumentation',
    ]);

    $this->makeEvaluation(
        $participant,
        $employee,
        $admin,
        $plcCompetency
    );

    $this->makeEvaluation(
        $participant,
        $employee,
        $admin,
        $instrumentCompetency
    );

    $this->actingAs($admin)
        ->get($this->reportUrl($training) . '?category=PLC')
        ->assertOk()
        ->assertViewHas('category', 'PLC')
        ->assertViewHas('totalEvaluations', 1)
        ->assertViewHas('evaluations', function ($evaluations) {
            return $evaluations->total() === 1
                && $evaluations->first()->competency_category === 'PLC';
        });
}
/**
 * Test 10:
 * Admin dapat mengunduh laporan evaluasi dalam PDF.
 */
public function test_admin_can_download_training_evaluation_pdf(): void
{
    $admin = $this->makeUser('admin');
    $employee = $this->makeUser();

    $training = $this->makeTraining();

    $participant = $this->makeParticipant(
        $training,
        $employee
    );

    $competency = $this->makeCompetency();

    $this->makeEvaluation(
        $participant,
        $employee,
        $admin,
        $competency
    );

    $response = $this->actingAs($admin)
        ->get(route('trainings.evaluations.export.pdf', [
            'training' => $training->id,
        ]));

    $response->assertOk();

    $this->assertSame(
        'application/pdf',
        $response->headers->get('content-type')
    );

    $this->assertStringContainsString(
        'attachment;',
        $response->headers->get('content-disposition')
    );
}

/**
 * Test 11:
 * Non-admin tidak dapat mengunduh laporan PDF.
 */
public function test_non_admin_cannot_download_training_evaluation_pdf(): void
{
    $employee = $this->makeUser();

    $training = $this->makeTraining();

    $this->actingAs($employee)
        ->get(route('trainings.evaluations.export.pdf', [
            'training' => $training->id,
        ]))
        ->assertForbidden();
}

/**
 * Test 12:
 * Guest tidak dapat mengunduh laporan PDF.
 */
public function test_guest_cannot_download_training_evaluation_pdf(): void
{
    $training = $this->makeTraining();

    $this->get(route('trainings.evaluations.export.pdf', [
        'training' => $training->id,
    ]))->assertRedirect(route('login'));
}
}