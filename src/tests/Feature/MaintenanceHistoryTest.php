<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\MaintenanceHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(
            \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class
        );
    }

    private function createEquipment(): Equipment
    {
        return Equipment::create([
            'equipment_code' => 'EQ-' . uniqid(),
            'equipment_name' => 'Pressure Transmitter',
            'tag_number' => 'PT-' . uniqid(),
            'category' => 'Instrument',
            'location' => 'Power Plant',
            'status' => 'Active',
        ]);
    }

    private function validPayload(): array
    {
        return [
            'maintenance_date' => '2026-10-20',
            'maintenance_type' => 'Preventive',
            'status' => 'Open',
            'technician' => 'Test Technician',
            'duration' => 60,
            'result' => 'Pending',
            'notes' => 'Automated test',
        ];
    }

    public function test_authenticated_user_can_create_maintenance_history(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $this->validPayload()
        );

        $response->assertRedirect(route('equipment.show', $equipment));

        $this->assertDatabaseHas('maintenance_histories', [
            'equipment_id' => $equipment->id,
            'maintenance_type' => 'Preventive',
            'technician' => 'Test Technician',
            'created_by' => $user->id,
        ]);
    }

    public function test_invalid_maintenance_type_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['maintenance_type'] = 'InvalidType';

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('maintenance_type');

        $this->assertDatabaseMissing('maintenance_histories', [
            'equipment_id' => $equipment->id,
        ]);
    }

    public function test_authenticated_user_can_update_maintenance_history(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $history = $equipment->maintenanceHistories()->create(
            array_merge($this->validPayload(), [
                'created_by' => $user->id,
            ])
        );

        $payload = $this->validPayload();
        $payload['status'] = 'Completed';
        $payload['result'] = 'Completed';

        $response = $this->actingAs($user)->put(
            route('equipment.maintenance-history.update', [
                'equipment' => $equipment->id,
                'history' => $history->id,
            ]),
            $payload
        );

        $response->assertRedirect(route('equipment.show', $equipment));

        $this->assertDatabaseHas('maintenance_histories', [
            'id' => $history->id,
            'status' => 'Completed',
            'result' => 'Completed',
        ]);
    }

    public function test_authenticated_user_can_delete_maintenance_history(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $history = $equipment->maintenanceHistories()->create(
            array_merge($this->validPayload(), [
                'created_by' => $user->id,
            ])
        );

        $response = $this->actingAs($user)->delete(
            route('equipment.maintenance-history.destroy', [
                'equipment' => $equipment->id,
                'history' => $history->id,
            ])
        );

        $response->assertRedirect(route('equipment.show', $equipment));

        $this->assertDatabaseMissing('maintenance_histories', [
            'id' => $history->id,
        ]);
    }

    public function test_history_cannot_be_updated_through_wrong_equipment(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();
        $otherEquipment = $this->createEquipment();

        $history = $equipment->maintenanceHistories()->create(
            array_merge($this->validPayload(), [
                'created_by' => $user->id,
            ])
        );

        $response = $this->actingAs($user)->put(
            route('equipment.maintenance-history.update', [
                'equipment' => $otherEquipment->id,
                'history' => $history->id,
            ]),
            $this->validPayload()
        );

        $response->assertNotFound();
    }

    public function test_invalid_status_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['status'] = 'InvalidStatus';

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseMissing('maintenance_histories', [
            'equipment_id' => $equipment->id,
        ]);
    }

    public function test_negative_duration_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['duration'] = -10;

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('duration');
    }

    public function test_completed_status_requires_completed_result_when_creating(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['status'] = 'Completed';
        $payload['result'] = 'Pending';

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('result');

        $this->assertDatabaseMissing('maintenance_histories', [
            'equipment_id' => $equipment->id,
            'status' => 'Completed',
        ]);
    }

    public function test_completed_status_requires_completed_result_when_updating(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $history = $equipment->maintenanceHistories()->create(
            array_merge($this->validPayload(), [
                'created_by' => $user->id,
            ])
        );

        $payload = $this->validPayload();
        $payload['status'] = 'Completed';
        $payload['result'] = 'Failed';

        $response = $this->actingAs($user)->put(
            route('equipment.maintenance-history.update', [
                'equipment' => $equipment->id,
                'history' => $history->id,
            ]),
            $payload
        );

        $response->assertSessionHasErrors('result');

        $this->assertDatabaseHas('maintenance_histories', [
            'id' => $history->id,
            'status' => 'Open',
            'result' => 'Pending',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_maintenance_history(): void
    {
        $equipment = $this->createEquipment();

        $response = $this->post(
            route('equipment.maintenance-history.store', $equipment),
            $this->validPayload()
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('maintenance_histories', [
            'equipment_id' => $equipment->id,
        ]);
    }

    public function test_history_cannot_be_deleted_through_wrong_equipment(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();
        $otherEquipment = $this->createEquipment();

        $history = $equipment->maintenanceHistories()->create(
            array_merge($this->validPayload(), [
                'created_by' => $user->id,
            ])
        );

        $response = $this->actingAs($user)->delete(
            route('equipment.maintenance-history.destroy', [
                'equipment' => $otherEquipment->id,
                'history' => $history->id,
            ])
        );

        $response->assertNotFound();

        $this->assertDatabaseHas('maintenance_histories', [
            'id' => $history->id,
            'equipment_id' => $equipment->id,
        ]);
    }

    public function test_missing_maintenance_date_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        unset($payload['maintenance_date']);

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('maintenance_date');
    }

    public function test_invalid_maintenance_date_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['maintenance_date'] = 'not-a-date';

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('maintenance_date');
    }

    public function test_missing_technician_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        unset($payload['technician']);

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('technician');
    }

    public function test_invalid_result_is_rejected(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment();

        $payload = $this->validPayload();
        $payload['result'] = 'InvalidResult';

        $response = $this->actingAs($user)->post(
            route('equipment.maintenance-history.store', $equipment),
            $payload
        );

        $response->assertSessionHasErrors('result');
    }
}
