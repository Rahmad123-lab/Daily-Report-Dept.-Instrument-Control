<?php

namespace Tests\Feature;

use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use App\Models\Competency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TrainingManagementTest extends TestCase
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

    private function makeTraining(
        array $attributes = []
    ): Training {
        return Training::create(array_merge([
            'title' => 'PLC Troubleshooting Training',
            'category' => 'Instrumentation & Control',
            'organizer' => 'Instrument & Control Department',
            'instructor' => 'Senior Instrument Engineer',
            'location' => 'Power Plant Workshop',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-11',
            'quota' => 10,
            'status' => 'planned',
            'description' => 'Training for automated testing.',
            'created_by' => null,
        ], $attributes));
    }

    private function makeParticipant(
        Training $training,
        User $user,
        array $attributes = []
    ): TrainingParticipant {
        return $training->participants()->create(array_merge([
            'user_id' => $user->id,
            'registration_status' => 'registered',
            'attendance_status' => 'not_marked',
            'notes' => null,
        ], $attributes));
    }

    public function test_active_user_can_view_training_index(): void
    {
        $user = $this->makeUser();
        $training = $this->makeTraining();

        $this->actingAs($user)
            ->get(route('trainings.index'))
            ->assertOk()
            ->assertSee($training->title);
    }

    public function test_active_user_can_register_for_training(): void
    {
        $user = $this->makeUser();
        $training = $this->makeTraining();

        $this->actingAs($user)
            ->post(route('trainings.participants.store', $training))
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('training_participants', [
            'training_id' => $training->id,
            'user_id' => $user->id,
            'registration_status' => 'registered',
            'attendance_status' => 'not_marked',
        ]);
    }

    public function test_duplicate_registration_is_rejected(): void
    {
        $user = $this->makeUser();
        $training = $this->makeTraining();

        $this->makeParticipant($training, $user);

        $this->actingAs($user)
            ->from(route('trainings.show', $training))
            ->post(route('trainings.participants.store', $training))
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('training_participants', 1);
    }

    public function test_registration_is_rejected_when_quota_is_full(): void
    {
        $training = $this->makeTraining([
            'quota' => 1,
        ]);

        $existingUser = $this->makeUser();
        $newUser = $this->makeUser();

        $this->makeParticipant($training, $existingUser, [
            'registration_status' => 'approved',
        ]);

        $this->actingAs($newUser)
            ->from(route('trainings.show', $training))
            ->post(route('trainings.participants.store', $training))
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('training_participants', [
            'training_id' => $training->id,
            'user_id' => $newUser->id,
        ]);
    }

    public function test_non_admin_cannot_approve_registration(): void
    {
        $user = $this->makeUser('karyawan');
        $training = $this->makeTraining();
        $participant = $this->makeParticipant(
            $training,
            $this->makeUser()
        );

        $this->actingAs($user)
            ->patch(route(
                'trainings.participants.registration.update',
                [$training, $participant]
            ), [
                'registration_status' => 'approved',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('training_participants', [
            'id' => $participant->id,
            'registration_status' => 'registered',
        ]);
    }

    public function test_admin_can_approve_registration(): void
    {
        $admin = $this->makeUser('admin');
        $training = $this->makeTraining();
        $participant = $this->makeParticipant(
            $training,
            $this->makeUser()
        );

        $this->actingAs($admin)
            ->patch(route(
                'trainings.participants.registration.update',
                [$training, $participant]
            ), [
                'registration_status' => 'approved',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('training_participants', [
            'id' => $participant->id,
            'registration_status' => 'approved',
        ]);
    }

    public function test_attendance_cannot_be_recorded_before_approval(): void
    {
        $admin = $this->makeUser('admin');
        $training = $this->makeTraining();
        $participant = $this->makeParticipant(
            $training,
            $this->makeUser(),
            [
                'registration_status' => 'registered',
            ]
        );

        $this->actingAs($admin)
            ->from(route('trainings.show', $training))
            ->patch(route(
                'trainings.participants.attendance.update',
                [$training, $participant]
            ), [
                'attendance_status' => 'present',
            ])
            ->assertRedirect(route('trainings.show', $training))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('training_participants', [
            'id' => $participant->id,
            'attendance_status' => 'not_marked',
        ]);
    }

    public function test_admin_can_record_attendance_for_approved_participant(): void
    {
        $admin = $this->makeUser('admin');
        $training = $this->makeTraining();
        $participant = $this->makeParticipant(
            $training,
            $this->makeUser(),
            [
                'registration_status' => 'approved',
            ]
        );

        $this->actingAs($admin)
            ->patch(route(
                'trainings.participants.attendance.update',
                [$training, $participant]
            ), [
                'attendance_status' => 'present',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('training_participants', [
            'id' => $participant->id,
            'registration_status' => 'approved',
            'attendance_status' => 'present',
        ]);
    }
    public function test_admin_can_create_training(): void
{
    $admin = $this->makeUser('admin');

    $payload = [
        'title' => 'Advanced PLC Troubleshooting',
        'category' => 'Instrumentation & Control',
        'organizer' => 'Instrument & Control Department',
        'instructor' => 'Senior Instrument Engineer',
        'location' => 'Power Plant Workshop',
        'start_date' => '2026-11-10',
        'end_date' => '2026-11-11',
        'quota' => 10,
        'status' => 'planned',
        'description' => 'Advanced PLC troubleshooting training.',
    ];

    $this->actingAs($admin)
        ->post(route('trainings.store'), $payload)
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('trainings', [
        'title' => 'Advanced PLC Troubleshooting',
        'created_by' => $admin->id,
        'status' => 'planned',
    ]);
}

public function test_non_admin_cannot_create_training(): void
{
    $user = $this->makeUser('karyawan');

    $this->actingAs($user)
        ->post(route('trainings.store'), [
            'title' => 'Unauthorized Training',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('trainings', [
        'title' => 'Unauthorized Training',
    ]);
}

public function test_training_end_date_cannot_be_before_start_date(): void
{
    $admin = $this->makeUser('admin');

    $this->actingAs($admin)
        ->from(route('trainings.create'))
        ->post(route('trainings.store'), [
            'title' => 'Invalid Training Schedule',
            'start_date' => '2026-11-12',
            'end_date' => '2026-11-10',
            'status' => 'planned',
            'quota' => 10,
        ])
        ->assertRedirect(route('trainings.create'))
        ->assertSessionHasErrors('end_date');

    $this->assertDatabaseMissing('trainings', [
        'title' => 'Invalid Training Schedule',
    ]);
}

public function test_admin_can_update_training(): void
{
    $admin = $this->makeUser('admin');
    $training = $this->makeTraining();

    $this->actingAs($admin)
        ->put(route('trainings.update', $training), [
            'title' => 'Updated PLC Training',
            'category' => 'Automation',
            'organizer' => 'Instrument & Control Department',
            'instructor' => 'Senior Engineer',
            'location' => 'Training Room',
            'start_date' => '2026-11-15',
            'end_date' => '2026-11-16',
            'quota' => 15,
            'status' => 'in_progress',
            'description' => 'Updated training description.',
        ])
        ->assertRedirect(route('trainings.show', $training))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('trainings', [
        'id' => $training->id,
        'title' => 'Updated PLC Training',
        'status' => 'in_progress',
        'quota' => 15,
    ]);
}

public function test_non_admin_cannot_delete_training(): void
{
    $user = $this->makeUser('karyawan');
    $training = $this->makeTraining();

    $this->actingAs($user)
        ->delete(route('trainings.destroy', $training))
        ->assertForbidden();

    $this->assertDatabaseHas('trainings', [
        'id' => $training->id,
    ]);
}

public function test_deleting_training_removes_its_participants(): void
{
    $admin = $this->makeUser('admin');
    $training = $this->makeTraining();
    $participantUser = $this->makeUser();

    $participant = $this->makeParticipant(
        $training,
        $participantUser
    );

    $this->actingAs($admin)
        ->delete(route('trainings.destroy', $training))
        ->assertRedirect(route('trainings.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('trainings', [
        'id' => $training->id,
    ]);

    $this->assertDatabaseMissing('training_participants', [
        'id' => $participant->id,
    ]);
}

public function test_completed_training_rejects_new_registration(): void
{
    $user = $this->makeUser();

    $training = $this->makeTraining([
        'status' => 'completed',
    ]);

    $this->actingAs($user)
        ->from(route('trainings.show', $training))
        ->post(route('trainings.participants.store', $training))
        ->assertRedirect(route('trainings.show', $training))
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('training_participants', [
        'training_id' => $training->id,
        'user_id' => $user->id,
    ]);
}

public function test_admin_cannot_update_participant_through_wrong_training(): void
{
    $admin = $this->makeUser('admin');

    $trainingA = $this->makeTraining([
        'title' => 'Training A',
    ]);

    $trainingB = $this->makeTraining([
        'title' => 'Training B',
    ]);

    $participant = $this->makeParticipant(
        $trainingB,
        $this->makeUser()
    );

    $this->actingAs($admin)
        ->patch(route(
            'trainings.participants.registration.update',
            [$trainingA, $participant]
        ), [
            'registration_status' => 'approved',
        ])
        ->assertNotFound();

    $this->assertDatabaseHas('training_participants', [
        'id' => $participant->id,
        'training_id' => $trainingB->id,
        'registration_status' => 'registered',
    ]);
}

public function test_admin_can_map_competencies_when_creating_training(): void
{
    $admin = $this->makeUser('admin');

    $competency = Competency::create([
        'code' => 'INST-PLC-002',
        'name' => 'PLC Troubleshooting',
        'category' => 'Instrumentation',
        'description' => 'PLC testing.',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->post(route('trainings.store'), [
            'title' => 'PLC Advanced Training',
            'category' => 'Automation',
            'organizer' => 'Instrument Department',
            'instructor' => 'Senior Engineer',
            'location' => 'Power Plant',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-11',
            'quota' => 10,
            'status' => 'planned',
            'description' => 'PLC training.',
            'competency_ids' => [$competency->id],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $training = Training::where(
        'title',
        'PLC Advanced Training'
    )->firstOrFail();

    $this->assertDatabaseHas('competency_training', [
        'training_id' => $training->id,
        'competency_id' => $competency->id,
    ]);
}

public function test_admin_can_update_training_competency_mapping(): void
{
    $admin = $this->makeUser('admin');
    $training = $this->makeTraining();

    $oldCompetency = Competency::create([
        'code' => 'INST-OLD-001',
        'name' => 'Old Competency',
        'category' => 'Instrumentation',
        'is_active' => true,
    ]);

    $newCompetency = Competency::create([
        'code' => 'INST-NEW-001',
        'name' => 'New Competency',
        'category' => 'Automation',
        'is_active' => true,
    ]);

    $training->competencies()->attach($oldCompetency->id);

    $this->actingAs($admin)
        ->put(route('trainings.update', $training), [
            'title' => 'Updated Training',
            'category' => 'Automation',
            'organizer' => 'Instrument Department',
            'instructor' => 'Senior Engineer',
            'location' => 'Power Plant',
            'start_date' => '2026-11-15',
            'end_date' => '2026-11-16',
            'quota' => 15,
            'status' => 'planned',
            'description' => 'Updated training.',
            'competency_ids' => [$newCompetency->id],
        ])
        ->assertRedirect(route('trainings.show', $training))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('competency_training', [
        'training_id' => $training->id,
        'competency_id' => $newCompetency->id,
    ]);

    $this->assertDatabaseMissing('competency_training', [
        'training_id' => $training->id,
        'competency_id' => $oldCompetency->id,
    ]);
}

public function test_inactive_competency_cannot_be_mapped_to_new_training(): void
{
    $admin = $this->makeUser('admin');

    $competency = Competency::create([
        'code' => 'INST-INACTIVE-001',
        'name' => 'Inactive Competency',
        'category' => 'Instrumentation',
        'is_active' => false,
    ]);

    $this->actingAs($admin)
        ->from(route('trainings.create'))
        ->post(route('trainings.store'), [
            'title' => 'Test Training',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-11',
            'status' => 'planned',
            'competency_ids' => [$competency->id],
        ])
        ->assertRedirect(route('trainings.create'))
        ->assertSessionHasErrors('competency_ids.0');

    $this->assertDatabaseMissing('trainings', [
        'title' => 'Test Training',
    ]);
}

public function test_existing_inactive_competency_mapping_is_preserved_on_update(): void
{
    $admin = $this->makeUser('admin');
    $training = $this->makeTraining();

    $competency = Competency::create([
        'code' => 'INST-ARCHIVE-001',
        'name' => 'Previously Mapped Competency',
        'category' => 'Instrumentation',
        'is_active' => false,
    ]);

    $training->competencies()->attach($competency->id);

    $this->actingAs($admin)
        ->put(route('trainings.update', $training), [
            'title' => 'Updated Training',
            'category' => 'Automation',
            'organizer' => 'Instrument Department',
            'instructor' => 'Senior Engineer',
            'location' => 'Power Plant',
            'start_date' => '2026-11-15',
            'end_date' => '2026-11-16',
            'quota' => 10,
            'status' => 'planned',
            'description' => 'Updated training.',
        ])
        ->assertRedirect(route('trainings.show', $training))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('competency_training', [
        'training_id' => $training->id,
        'competency_id' => $competency->id,
    ]);
}

}